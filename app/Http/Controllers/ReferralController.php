<?php

namespace App\Http\Controllers;

use App\Models\ReferralReward;
use App\Models\Setting;
use App\Models\User;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    protected $referralService;
    
    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }
    
    /**
     * Display the referral program page.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get the actual referral reward amount from settings - fix by directly accessing 'referral_reward' setting
        $referralRewardAmount = Setting::where('key', 'referral_reward')->first()?->value ?? 100;
        $referralRewardPercentage = Setting::where('key', 'referral_percentage')->first()?->value ?? 5;
        
        // Get referred users without preloading the rewards
        $referredUsers = User::where('referred_by', $user->id)
            ->withCount('spins')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Calculate total fixed referral earnings
        $totalFixedReferralEarnings = $user->referralRewards()
            ->where('is_percentage_reward', false)
            ->sum('coins_earned');
            
        // Calculate total percentage referral earnings
        $totalPercentageReferralEarnings = $user->referralRewards()
            ->where('is_percentage_reward', true)
            ->sum('coins_earned');
            
        // Total of both types
        $totalReferralEarnings = $totalFixedReferralEarnings + $totalPercentageReferralEarnings;
        
        // Get all referral rewards earned by this user, grouped by referral_id
        $rewardsMap = $user->referralRewards()
            ->select('referral_id', \DB::raw('SUM(coins_earned) as total_rewards'))
            ->groupBy('referral_id')
            ->pluck('total_rewards', 'referral_id')
            ->toArray();
            
        // Attach reward information to each referred user
        foreach ($referredUsers as $referral) {
            $referral->has_rewards = isset($rewardsMap[$referral->id]);
            $referral->total_rewards = $rewardsMap[$referral->id] ?? 0;
        }
        
        // Generate Telegram referral link
        $telegramReferralLink = app(ReferralService::class)->generateTelegramReferralLink($user);
        
        return view('user.referrals.index', compact(
            'user', 
            'referredUsers', 
            'referralRewardAmount',
            'referralRewardPercentage',
            'totalReferralEarnings',
            'totalFixedReferralEarnings',
            'totalPercentageReferralEarnings',
            'telegramReferralLink'
        ));
    }
    
    /**
     * Process a referral when user registers.
     */
    public function processReferral($user, $referralCode)
    {
        return $this->referralService->processInitialReferral($user, $referralCode);
    }
} 