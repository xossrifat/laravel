<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\VideoWatch;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdsterraController extends Controller
{
    /**
     * Show the Adsterra monitoring dashboard.
     */
    public function index()
    {
        $settings = [
            'adsterra_publisher_id' => Setting::where('key', 'adsterra_publisher_id')->first()?->value,
            'adsterra_banner_id' => Setting::where('key', 'adsterra_banner_id')->first()?->value,
            'adsterra_interstitial_id' => Setting::where('key', 'adsterra_interstitial_id')->first()?->value,
            'adsterra_social_id' => Setting::where('key', 'adsterra_social_id')->first()?->value,
        ];

        // Get today's stats
        $today = Carbon::today();
        $videoWatchesToday = VideoWatch::whereDate('created_at', $today)->count();
        $uniqueViewersToday = VideoWatch::whereDate('created_at', $today)
            ->distinct('user_id')
            ->count();
        $coinsAwardedToday = VideoWatch::whereDate('created_at', $today)
            ->sum('coins_earned');

        // Get weekly stats
        $startOfWeek = Carbon::now()->startOfWeek();
        $weeklyStats = VideoWatch::where('created_at', '>=', $startOfWeek)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total_views, COUNT(DISTINCT user_id) as unique_viewers, SUM(coins_earned) as coins_awarded')
            ->groupBy('date')
            ->get();

        return view('admin.adsterra.index', compact(
            'settings',
            'videoWatchesToday',
            'uniqueViewersToday',
            'coinsAwardedToday',
            'weeklyStats'
        ));
    }

    /**
     * Show detailed reports.
     */
    public function reports()
    {
        $monthlyStats = VideoWatch::selectRaw('
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            COUNT(*) as total_views,
            COUNT(DISTINCT user_id) as unique_viewers,
            SUM(coins_earned) as coins_awarded
        ')
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->paginate(12);

        return view('admin.adsterra.reports', compact('monthlyStats'));
    }

    /**
     * Show earnings analytics.
     */
    public function earnings()
    {
        // This would typically integrate with Adsterra's API
        // For now, we'll show placeholder data
        $earnings = [
            'today' => 0,
            'this_week' => 0,
            'this_month' => 0,
            'total' => 0
        ];

        return view('admin.adsterra.earnings', compact('earnings'));
    }

    /**
     * Show user engagement statistics.
     */
    public function userStats()
    {
        $topViewers = VideoWatch::selectRaw('
            user_id,
            COUNT(*) as total_views,
            SUM(coins_earned) as total_coins
        ')
        ->with('user:id,name,email')
        ->groupBy('user_id')
        ->orderByDesc('total_views')
        ->limit(10)
        ->get();

        $viewTimeDistribution = VideoWatch::selectRaw('
            HOUR(created_at) as hour,
            COUNT(*) as views
        ')
        ->groupBy('hour')
        ->orderBy('hour')
        ->get();

        return view('admin.adsterra.user-stats', compact('topViewers', 'viewTimeDistribution'));
    }
} 