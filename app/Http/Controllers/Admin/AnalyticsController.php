<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Spin;
use App\Models\VideoWatch;
use App\Models\Withdraw;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard.
     */
    public function index()
    {
        // Get period for statistics
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        // User statistics
        $totalUsers = User::count();
        $newUsersThisMonth = User::where('created_at', '>=', $startDate)->count();
        $activeUsersThisMonth = User::whereDate('last_login_at', '>=', $startDate)->count();

        // Spin statistics
        $totalSpins = Spin::count();
        $spinsThisMonth = Spin::where('created_at', '>=', $startDate)->count();
        $averageSpinsPerUser = $totalUsers > 0 ? round($totalSpins / $totalUsers, 2) : 0;

        // Video statistics
        $totalVideos = VideoWatch::count();
        $videosThisMonth = VideoWatch::where('created_at', '>=', $startDate)->count();
        $averageVideosPerUser = $totalUsers > 0 ? round($totalVideos / $totalUsers, 2) : 0;

        // Withdrawal statistics
        $totalPaid = Withdraw::where('status', 'approved')->sum('amount');
        $pendingAmount = Withdraw::where('status', 'pending')->sum('amount');
        $withdrawalsThisMonth = Withdraw::where('created_at', '>=', $startDate)->count();

        // Daily user activity trends
        $dailyUserActivity = User::select(DB::raw('DATE(last_login_at) as date'), DB::raw('COUNT(*) as count'))
            ->whereDate('last_login_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Daily spin trends
        $dailySpinActivity = Spin::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->whereDate('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.analytics.index', compact(
            'totalUsers',
            'newUsersThisMonth',
            'activeUsersThisMonth',
            'totalSpins',
            'spinsThisMonth',
            'averageSpinsPerUser',
            'totalVideos',
            'videosThisMonth',
            'averageVideosPerUser',
            'totalPaid',
            'pendingAmount',
            'withdrawalsThisMonth',
            'dailyUserActivity',
            'dailySpinActivity'
        ));
    }

    /**
     * Display active users analytics.
     */
    public function activeUsers()
    {
        $daily = User::select(DB::raw('DATE(last_login_at) as date'), DB::raw('COUNT(*) as count'))
            ->whereDate('last_login_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $monthly = User::select(
                DB::raw('YEAR(last_login_at) as year'), 
                DB::raw('MONTH(last_login_at) as month'), 
                DB::raw('COUNT(DISTINCT id) as count')
            )
            ->whereDate('last_login_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // New users over time
        $newUsers = User::select(
                DB::raw('YEAR(created_at) as year'), 
                DB::raw('MONTH(created_at) as month'), 
                DB::raw('COUNT(*) as count')
            )
            ->whereDate('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('admin.analytics.active-users', compact('daily', 'monthly', 'newUsers'));
    }

    /**
     * Display spin analytics.
     */
    public function spins()
    {
        $daily = Spin::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->whereDate('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $monthly = Spin::select(
                DB::raw('YEAR(created_at) as year'), 
                DB::raw('MONTH(created_at) as month'), 
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(coins_won) as total_coins')
            )
            ->whereDate('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $rewardDistribution = Spin::select('reward_id', DB::raw('COUNT(*) as count'))
            ->with('reward')
            ->groupBy('reward_id')
            ->get();

        return view('admin.analytics.spins', compact('daily', 'monthly', 'rewardDistribution'));
    }

    /**
     * Display video analytics.
     */
    public function videos()
    {
        $daily = VideoWatch::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->whereDate('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $monthly = VideoWatch::select(
                DB::raw('YEAR(created_at) as year'), 
                DB::raw('MONTH(created_at) as month'), 
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(coins_earned) as total_coins')
            )
            ->whereDate('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $userDistribution = VideoWatch::select('user_id', DB::raw('COUNT(*) as count'))
            ->with('user:id,name')
            ->whereDate('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('user_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.analytics.videos', compact('daily', 'monthly', 'userDistribution'));
    }

    /**
     * Display earnings analytics.
     */
    public function earnings()
    {
        $monthly = Withdraw::select(
                DB::raw('YEAR(created_at) as year'), 
                DB::raw('MONTH(created_at) as month'), 
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CASE WHEN status = "approved" THEN amount ELSE 0 END) as total_approved'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN amount ELSE 0 END) as total_pending'),
                DB::raw('SUM(CASE WHEN status = "rejected" THEN amount ELSE 0 END) as total_rejected')
            )
            ->whereDate('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $coinRate = Setting::where('key', 'coin_rate')->first()?->value ?? 1000;
        $totalCoinsEarned = Spin::sum('coins_won') + VideoWatch::sum('coins_earned');
        $totalMoneyPaid = Withdraw::where('status', 'approved')->sum('amount');
        $pendingAmount = Withdraw::where('status', 'pending')->sum('amount');

        return view('admin.analytics.earnings', compact('monthly', 'coinRate', 'totalCoinsEarned', 'totalMoneyPaid', 'pendingAmount'));
    }

    /**
     * Display popular rewards analytics.
     */
    public function popularRewards()
    {
        $rewardDistribution = Spin::select('reward_id', DB::raw('COUNT(*) as count'))
            ->with('reward')
            ->groupBy('reward_id')
            ->get();

        $dailyRewards = Spin::select(
                DB::raw('DATE(created_at) as date'), 
                'reward_id',
                DB::raw('COUNT(*) as count')
            )
            ->with('reward')
            ->whereDate('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date', 'reward_id')
            ->orderBy('date')
            ->get()
            ->groupBy('date');

        return view('admin.analytics.popular-rewards', compact('rewardDistribution', 'dailyRewards'));
    }
}
