<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\VideoWatch;
use App\Models\VideoAdFixed;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\VideoAd;
use App\Services\ReferralService;

class AdController extends Controller
{
    public function show()
    {
        // Get video watch settings from admin configuration
        $dailyLimit = Setting::get('daily_video_limit', 3);
        $coinReward = Setting::get('video_watch_reward', 10);
        $allowFallback = Setting::get('allow_ad_fallback', true);
        $adViewTime = Setting::get('ad_view_time', 10);
        $adProvider = Setting::get('ad_provider', 'adsterra');
        
        // Get ad provider specific settings
        $adSettings = [];
        
        if ($adProvider == 'unity') {
            $adSettings = [
                'gameId' => Setting::get('unity_game_id', ''),
                'placementId' => Setting::get('unity_placement_id', 'Interstitial_Android'),
                'testMode' => Setting::get('unity_test_mode', true),
            ];
        } else {
            // Get random ad from VideoAd model based on priority
            $videoAd = VideoAdFixed::getRandomAd();
            
            // Use legacy settings as fallback if no video ads are configured
            if (!$videoAd) {
                // Get Adsterra settings
                $adFormat = Setting::get('adsterra_format', 'popunder');
                $adUrl = '';
                
                // Get format-specific URL if available, otherwise use default
                switch ($adFormat) {
                    case 'popunder':
                        $adUrl = Setting::get('adsterra_popunder_url', '');
                        break;
                    case 'native':
                        $adUrl = Setting::get('adsterra_native_url', '');
                        break;
                    case 'banner':
                        $adUrl = Setting::get('adsterra_banner_url', '');
                        break;
                    case 'social':
                        $adUrl = Setting::get('adsterra_social_url', '');
                        break;
                }
                
                // If no format-specific URL, use default
                if (empty($adUrl)) {
                    $adUrl = Setting::get('adsterra_default_url', 'https://www.profitableratecpm.com/v43xzwwb?key=c5b754a5645ad87bfd987a4b558b338e');
                }
                
                // Get video ad URL from settings
                $videoAdUrl = Setting::get('video_ad_url', $adUrl);
                
                $adSettings = [
                    'format' => $adFormat,
                    'adUrl' => $adUrl,
                    'videoAdUrl' => $videoAdUrl,
                    'type' => 'url',
                    'isLegacy' => true,
                ];
            } else {
                $adSettings = [
                    'adId' => $videoAd->id,
                    'name' => $videoAd->name,
                    'content' => $videoAd->content,
                    'type' => $videoAd->type,
                    'isLegacy' => false,
                ];
            }
        }
        
        // Count today's watches
        $user = Auth::user();
        $watchesToday = VideoWatch::where('user_id', $user->id)
                        ->whereDate('created_at', Carbon::today())
                        ->count();
        
        $watchesLeft = max(0, $dailyLimit - $watchesToday);
        
        return view('ads.show', compact('watchesLeft', 'coinReward', 'allowFallback', 'adProvider', 'adSettings', 'adViewTime'));
    }
    
    public function watch(Request $request)
    {
        $user = Auth::user();
        
        // Get reward amount from settings
        $coinReward = Setting::get('video_watch_reward', 10);
        
        // Check if ad was completed
        $adCompleted = (bool)$request->input('ad_completed', 0);
        $fallbackUsed = (bool)$request->input('fallback_used', 0);
        
        // Get whether fallback is allowed from settings
        $allowFallback = (bool)Setting::get('allow_ad_fallback', true);
        
        Log::info('Ad watch attempt', [
            'user_id' => $user->id,
            'ad_completed' => $adCompleted,
            'fallback_used' => $fallbackUsed,
            'allow_fallback' => $allowFallback,
        ]);
        
        // Allow reward if ad was completed or fallback was used (and allowed)
        if (!$adCompleted && !($fallbackUsed && $allowFallback)) {
            return back()->with('error', 'You need to watch the entire ad to earn coins.');
        }
        
        // Record the watch
        $source = $fallbackUsed ? 'fallback' : 'normal';
        $videoId = $request->input('ad_id') ? 'ad-' . $request->input('ad_id') : $source . '-' . uniqid();
        
        VideoWatch::create([
            'user_id' => $user->id,
            'coins_earned' => $coinReward,
            'video_id' => $videoId,
        ]);
        
        // Add coins to user
        $user->increment('coins', $coinReward);
        
        // Process referral percentage earnings
        app(ReferralService::class)->processPercentageReward($user, $coinReward, 'video_watch');
        
        return back()->with('success', "You earned {$coinReward} coins for watching the ad!");
    }
}
