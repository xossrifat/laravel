<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Spin;
use App\Models\VideoWatch;
use App\Models\ReferralReward;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::withCount(['spins', 'videoWatches', 'withdrawals', 'referrals'])
            ->with('referrer:id,name,email')
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Toggle user ban status.
     */
    public function toggleBan(User $user)
    {
        $wasBanned = $user->is_banned;
        $user->update(['is_banned' => !$wasBanned]);
        
        // If user is being banned (not unbanned), create a ban notification
        if (!$wasBanned) {
            $user->notifications()->create([
                'title' => 'Your account has been suspended',
                'message' => 'Your account has been suspended due to violation of our terms of service. If you believe this is an error, please submit a support request for account review.',
                'type' => 'ban'
            ]);
        } 
        // If user is being unbanned, create an unban notification
        else {
            $user->notifications()->create([
                'title' => 'Your account has been restored',
                'message' => 'Your account has been restored and you can now use all features of the application. Thank you for your patience.',
                'type' => 'system'
            ]);
        }
        
        return back()->with('success', 
            $user->is_banned ? 'User has been banned.' : 'User has been unbanned.'
        );
    }

    /**
     * Show user's coin balance.
     */
    public function showCoins(User $user)
    {
        $transactions = collect([
            ...$user->spins()->latest()->take(10)->get()->map(function($spin) {
                return [
                    'type' => 'Spin Reward',
                    'amount' => $spin->coins_won,
                    'date' => $spin->created_at,
                ];
            }),
            ...$user->videoWatches()->latest()->take(10)->get()->map(function($watch) {
                return [
                    'type' => 'Video Watch',
                    'amount' => $watch->coins_earned,
                    'date' => $watch->created_at,
                ];
            }),
            ...$user->referralRewards()->latest()->take(10)->get()->map(function($reward) {
                return [
                    'type' => 'Referral Reward',
                    'amount' => $reward->coins_earned,
                    'date' => $reward->created_at,
                ];
            }),
        ])->sortByDesc('date')->take(10);

        return view('admin.users.coins', compact('user', 'transactions'));
    }

    /**
     * Update user's coin balance.
     */
    public function updateCoins(Request $request, User $user)
    {
        $validated = $request->validate([
            'action' => 'required|in:add,subtract',
            'amount' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        if ($validated['action'] === 'add') {
            $user->addCoins($validated['amount']);
            $message = "Added {$validated['amount']} coins to user's balance.";
        } else {
            if ($user->removeCoins($validated['amount'])) {
                $message = "Removed {$validated['amount']} coins from user's balance.";
            } else {
                return back()->with('error', 'User does not have enough coins.');
            }
        }

        return back()->with('success', $message);
    }

    /**
     * Show user's spin history.
     */
    public function spinHistory(User $user)
    {
        $spins = $user->spins()
            ->with('reward')
            ->latest()
            ->paginate(15);

        return view('admin.users.spins', compact('user', 'spins'));
    }

    /**
     * Show user's video watch history.
     */
    public function videoHistory(User $user)
    {
        $videos = $user->videoWatches()
            ->latest()
            ->paginate(15);

        return view('admin.users.videos', compact('user', 'videos'));
    }
    
    /**
     * Show user's referral details
     */
    public function referralDetails(User $user)
    {
        $referrer = $user->referrer;
        $referrals = $user->referrals()->with('referralRewards')->paginate(15);
        $referralRewards = ReferralReward::where('user_id', $user->id)->with('referral')->latest()->paginate(15);
        
        return view('admin.users.referrals', compact('user', 'referrer', 'referrals', 'referralRewards'));
    }
} 