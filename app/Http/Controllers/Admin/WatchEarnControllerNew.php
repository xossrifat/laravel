<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\VideoAdFixed;
use Illuminate\Http\Request;

class WatchEarnControllerNew extends Controller
{
    /**
     * Show the watch & earn configuration page.
     */
    public function index()
    {
        $settings = [
            'unity_game_id' => Setting::where('key', 'unity_game_id')->first()?->value,
            'unity_placement_id' => Setting::where('key', 'unity_placement_id')->first()?->value,
            'unity_test_mode' => Setting::where('key', 'unity_test_mode')->first()?->value ?? false,
            'allow_ad_fallback' => Setting::where('key', 'allow_ad_fallback')->first()?->value ?? true,
            'video_watch_reward' => Setting::where('key', 'video_watch_reward')->first()?->value ?? 10,
            'daily_video_limit' => Setting::where('key', 'daily_video_limit')->first()?->value ?? 3,
            // Default ad provider
            'ad_provider' => Setting::where('key', 'ad_provider')->first()?->value ?? 'adsterra',
            'ad_view_time' => Setting::where('key', 'ad_view_time')->first()?->value ?? 10,
            
            // Adsterra ad formats and URLs
            'adsterra_popunder_url' => Setting::where('key', 'adsterra_popunder_url')->first()?->value ?? '',
            'adsterra_native_url' => Setting::where('key', 'adsterra_native_url')->first()?->value ?? '',
            'adsterra_banner_url' => Setting::where('key', 'adsterra_banner_url')->first()?->value ?? '',
            'adsterra_social_url' => Setting::where('key', 'adsterra_social_url')->first()?->value ?? '',
            'adsterra_format' => Setting::where('key', 'adsterra_format')->first()?->value ?? 'popunder',
            'adsterra_default_url' => Setting::where('key', 'adsterra_default_url')->first()?->value ?? 'https://www.profitableratecpm.com/v43xzwwb?key=c5b754a5645ad87bfd987a4b558b338e',
            
            // Video ad URL specifically for the video ads page
            'video_ad_url' => Setting::where('key', 'video_ad_url')->first()?->value ?? '',
        ];

        // Get all video ads for the video ads table
        $videoAds = VideoAdFixed::orderBy('priority', 'desc')->get();
        
        // Calculate total priority
        $totalPriority = $videoAds->sum('priority');

        return view('admin.watch-earn.index', compact('settings', 'videoAds', 'totalPriority'));
    }

    /**
     * Update ad configuration.
     */
    public function updateConfig(Request $request)
    {
        $request->validate([
            'unity_game_id' => 'nullable|string',
            'unity_placement_id' => 'nullable|string',
            'ad_provider' => 'required|string|in:unity,adsterra',
        ]);

        // Save Unity Game ID and Placement ID
        foreach ($request->only([
            'unity_game_id',
            'unity_placement_id',
            'ad_provider',
        ]) as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Handle test mode checkbox
        Setting::updateOrCreate(
            ['key' => 'unity_test_mode'],
            ['value' => $request->has('unity_test_mode') ? '1' : '0']
        );
        
        // Handle fallback option checkbox
        Setting::updateOrCreate(
            ['key' => 'allow_ad_fallback'],
            ['value' => $request->has('allow_ad_fallback') ? '1' : '0']
        );

        return back()->with('success', 'Ad configuration updated successfully.');
    }

    /**
     * Update Adsterra ad format settings.
     */
    public function updateAdsterra(Request $request)
    {
        $request->validate([
            'adsterra_format' => 'required|string|in:popunder,native,banner,social',
            'adsterra_popunder_url' => 'nullable|url',
            'adsterra_native_url' => 'nullable|url',
            'adsterra_banner_url' => 'nullable|url',
            'adsterra_social_url' => 'nullable|url',
            'adsterra_default_url' => 'required|url',
            'video_ad_url' => 'nullable|url',
        ]);

        // Save Adsterra settings
        foreach ($request->only([
            'adsterra_format',
            'adsterra_popunder_url',
            'adsterra_native_url',
            'adsterra_banner_url',
            'adsterra_social_url',
            'adsterra_default_url',
            'video_ad_url',
        ]) as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Adsterra ad settings updated successfully.');
    }

    /**
     * Update video rewards.
     */
    public function updateRewards(Request $request)
    {
        $request->validate([
            'video_watch_reward' => 'required|integer|min:1',
        ]);

        Setting::updateOrCreate(
            ['key' => 'video_watch_reward'],
            ['value' => $request->video_watch_reward]
        );

        return back()->with('success', 'Video rewards updated successfully.');
    }

    /**
     * Update daily video limits.
     */
    public function updateLimits(Request $request)
    {
        $request->validate([
            'daily_video_limit' => 'required|integer|min:1',
            'ad_view_time' => 'required|integer|min:5|max:60',
        ]);

        Setting::updateOrCreate(
            ['key' => 'daily_video_limit'],
            ['value' => $request->daily_video_limit]
        );

        Setting::updateOrCreate(
            ['key' => 'ad_view_time'],
            ['value' => $request->ad_view_time]
        );

        return back()->with('success', 'Daily video limit and view time updated successfully.');
    }
    
    /**
     * Store a new video ad.
     */
    public function storeVideoAd(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:url,script',
            'priority' => 'required|integer|min:1|max:100',
        ]);
        
        // Create the new video ad
        VideoAdFixed::create([
            'name' => $request->name,
            'content' => $request->content,
            'type' => $request->type,
            'priority' => $request->priority,
            'is_active' => $request->has('is_active'),
        ]);
        
        return back()->with('success', 'Video ad added successfully.');
    }
    
    /**
     * Update an existing video ad.
     */
    public function updateVideoAd(Request $request, VideoAdFixed $videoAd)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:url,script',
            'priority' => 'required|integer|min:1|max:100',
        ]);
        
        // Update the video ad
        $videoAd->update([
            'name' => $request->name,
            'content' => $request->content,
            'type' => $request->type,
            'priority' => $request->priority,
            'is_active' => $request->has('is_active'),
        ]);
        
        return back()->with('success', 'Video ad updated successfully.');
    }
    
    /**
     * Toggle the active status of a video ad.
     */
    public function toggleVideoAd(VideoAdFixed $videoAd)
    {
        $videoAd->update([
            'is_active' => !$videoAd->is_active,
        ]);
        
        $status = $videoAd->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Video ad {$status} successfully.");
    }
    
    /**
     * Delete a video ad.
     */
    public function deleteVideoAd(VideoAdFixed $videoAd)
    {
        $videoAd->delete();
        
        return back()->with('success', 'Video ad deleted successfully.');
    }
}
