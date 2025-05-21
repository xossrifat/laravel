<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\Spin;
use App\Models\VideoWatch;
use App\Models\Withdraw;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Check if user is logged in
        if (!auth()->check()) {
            // For non-authenticated users, show public dashboard
            $maintenanceModeSetting = Setting::where('key', 'maintenance_mode')->first()?->value;
            $maintenanceMode = ($maintenanceModeSetting === 'true' || $maintenanceModeSetting === '1');
            
            // Detect if user is on mobile
            $agent = new Agent();
            $isMobile = $agent->isMobile();
            
            if ($isMobile) {
                return view('mobile.public_dashboard', compact('maintenanceMode'));
            }
            
            return view('public_dashboard', compact('maintenanceMode'));
        }
        
        // For authenticated users, continue with normal dashboard
        $user = auth()->user();
        
        // Get settings
        $coinRate = Setting::where('key', 'coin_rate')->first()?->value ?? 1000;
        $maxSpins = Setting::where('key', 'daily_spin_limit')->first()?->value ?? 5;
        $maxAds = Setting::where('key', 'daily_video_limit')->first()?->value ?? 10;
        $minWithdraw = Setting::where('key', 'min_withdraw_amount')->first()?->value ?? 10;
        
        // Get maintenance mode setting properly
        $maintenanceModeSetting = Setting::where('key', 'maintenance_mode')->first()?->value;
        $maintenanceMode = ($maintenanceModeSetting === 'true' || $maintenanceModeSetting === '1');
        
        // Calculate money equivalent
        $moneyEquivalent = $user->coins / $coinRate;
        
        // Get spins left today
        $spinsToday = Spin::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->count();
        $spinsLeft = $maxSpins - $spinsToday;
        $spinsLeft = max(0, $spinsLeft);
        
        // Get video ads left today
        $adsToday = VideoWatch::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->count();
        $adsLeft = $maxAds - $adsToday;
        $adsLeft = max(0, $adsLeft);
        
        // Get recent spins
        $recentSpins = Spin::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
            
        // Get recent withdrawals
        $recentWithdraws = Withdraw::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        
        // Detect if user is on mobile
        $agent = new Agent();
        $isMobile = $agent->isMobile();
        
        $viewData = compact(
            'user', 
            'coinRate', 
            'moneyEquivalent', 
            'spinsLeft', 
            'adsLeft', 
            'maxSpins', 
            'maxAds', 
            'recentSpins', 
            'recentWithdraws', 
            'minWithdraw',
            'maintenanceMode'
        );
        
        // Return mobile-specific view if on mobile device
        if ($isMobile) {
            return view('mobile.dashboard', $viewData);
        }
        
        return view('dashboard', $viewData);
    }
}
