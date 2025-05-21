<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SpinReward;
use App\Models\Setting;
use App\Models\Spin;
use App\Models\User;
use App\Services\ReferralService;
use Illuminate\Support\Facades\Log;

class SpinController extends Controller
{
    public function index()
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            // Redirect to login page with a message
            return redirect()->route('login')->with('message', 'Please login to use the Spin feature.');
        }
        
        $user = auth()->user();
        
        // Get all available rewards from the database
        $rewards = SpinReward::all();
        
        // Get daily spin limit from settings
        $dailySpinLimit = Setting::get('daily_spin_limit', 5);
        
        // Get user's spins today
        $spinsToday = Spin::where('user_id', $user->id)
                         ->whereDate('created_at', now()->toDateString())
                         ->count();
        
        $spinsLeft = max(0, $dailySpinLimit - $spinsToday);
        
        // Get spin ads settings
        $spinAdsEnabled = (bool)Setting::get('spin_ads_enabled', false);
        $spinAdUrls = Setting::getJson('spin_ad_urls', []);
        
        return view('spin.index', compact('rewards', 'spinsLeft', 'spinAdsEnabled', 'spinAdUrls'));
    }

    public function spin(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Check if user has reached daily limit
            $dailySpinLimit = (int) Setting::where('key', 'daily_spin_limit')->first()?->value ?? 5;
            $todaySpins = Spin::where('user_id', $user->id)
                              ->whereDate('created_at', today())
                              ->count();
            
            if ($todaySpins >= $dailySpinLimit) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have reached your daily spin limit.',
                    'spins_left' => 0
                ], 403);
            }
            
            // Determine the reward
            $spinRewards = SpinReward::all();
            
            if ($spinRewards->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No rewards configured.',
                ], 500);
            }
            
            // Randomly select a reward based on probability
            $selectedReward = $this->selectRandomReward($spinRewards);
            
            // Create spin record
            $spin = Spin::create([
                'user_id' => $user->id,
                'reward_id' => $selectedReward->id,
                'coins_earned' => $selectedReward->coins
            ]);
            
            // Add coins to user's balance
            $user->addCoins($selectedReward->coins);
            
            // Process referral percentage earnings
            app(ReferralService::class)->processPercentageReward($user, $selectedReward->coins, 'spin');
            
            // Decrement available spins
            $spinsLeft = $dailySpinLimit - ($todaySpins + 1);
            
            return response()->json([
                'success' => true,
                'message' => 'You won ' . $selectedReward->coins . ' coins!',
                'reward' => $selectedReward,
                'spins_left' => $spinsLeft
            ]);
            
        } catch (\Exception $e) {
            Log::error('Spin error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your spin.',
            ], 500);
        }
    }

    public function submit(Request $request)
    {
        $user = Auth::user();

        // Get daily spin limit from settings
        $dailySpinLimit = Setting::get('daily_spin_limit', 5);
        
        // Get user's spins today
        $spinsToday = Spin::where('user_id', $user->id)
                         ->whereDate('created_at', now()->toDateString())
                         ->count();
        
        // Check if user has reached daily limit
        if ($spinsToday >= $dailySpinLimit) {
            return response()->json([
                'success' => false,
                'message' => 'You have reached your daily spin limit.'
            ]);
        }

        // Get all rewards from database with their probabilities
        $rewards = SpinReward::all();
        
        if ($rewards->isEmpty()) {
            // Fallback if no rewards are configured - ensure minimum 10 coins
            $reward = 10;
            $rewardId = null;
            $segmentIndex = 0;
            
            // Create a default reward if none exist
            $defaultReward = SpinReward::create([
                'coins' => 10,
                'label' => 'Default Reward',
                'probability' => 100
            ]);
            
            $rewardId = $defaultReward->id;
        } else {
            // Calculate which reward to give based on probabilities
            $rand = mt_rand(1, 100);
            $accumulatedProbability = 0;
            $reward = null;
            $rewardId = null;
            $segmentIndex = 0;
            
            foreach ($rewards as $index => $r) {
                $accumulatedProbability += $r->probability;
                if ($rand <= $accumulatedProbability) {
                    // Ensure minimum 5 coins even if configured for less
                    $reward = max(5, $r->coins); 
                    $rewardId = $r->id;
                    $segmentIndex = $index;
                    break;
                }
            }
            
            // Fallback if probabilities don't add up to 100
            if ($reward === null) {
                $firstReward = $rewards->first();
                // Ensure minimum 5 coins
                $reward = max(5, $firstReward->coins); 
                $rewardId = $firstReward->id;
                $segmentIndex = 0;
            }
        }

        // Record the spin in the database - ensure coins_won is never 0
        Spin::create([
            'user_id' => $user->id,
            'reward_id' => $rewardId,
            'coins_won' => max(5, $reward) // Ensure at least 5 coins are won
        ]);

        // Update user's coins
        $user->increment('coins', $reward);
        
        // Process referral percentage earnings
        app(ReferralService::class)->processPercentageReward($user, $reward, 'spin');

        return response()->json([
            'success' => true,
            'reward' => $reward,
            'coins' => $user->coins,
            'segmentIndex' => $segmentIndex,
            'totalSegments' => $rewards->count(),
        ]);
    }
}
