<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class GlobalAdsController extends Controller
{
    /**
     * Show the global ads configuration page.
     */
    public function index()
    {
        $settings = [
            'global_ads_enabled' => Setting::where('key', 'global_ads_enabled')->first()?->value ?? true,
            
            // Banner ad settings
            'global_banner_ad_enabled' => Setting::where('key', 'global_banner_ad_enabled')->first()?->value ?? false,
            'global_banner_ad_code' => Setting::where('key', 'global_banner_ad_code')->first()?->value ?? '',
            
            // Native ad settings
            'global_native_ad_enabled' => Setting::where('key', 'global_native_ad_enabled')->first()?->value ?? false,
            'global_native_ad_code' => Setting::where('key', 'global_native_ad_code')->first()?->value ?? '',
            
            // Social bar ad settings
            'global_social_ad_enabled' => Setting::where('key', 'global_social_ad_enabled')->first()?->value ?? false,
            'global_social_ad_code' => Setting::where('key', 'global_social_ad_code')->first()?->value ?? '',
            
            // Sidebar ad settings
            'left_sidebar_ad_enabled' => Setting::where('key', 'left_sidebar_ad_enabled')->first()?->value ?? false,
            'left_sidebar_ad_code' => Setting::where('key', 'left_sidebar_ad_code')->first()?->value ?? '',
            'right_sidebar_ad_enabled' => Setting::where('key', 'right_sidebar_ad_enabled')->first()?->value ?? false,
            'right_sidebar_ad_code' => Setting::where('key', 'right_sidebar_ad_code')->first()?->value ?? '',
            'right_sidebar_ad_height' => Setting::where('key', 'right_sidebar_ad_height')->first()?->value ?? 60,
            'right_sidebar_ad_width' => Setting::where('key', 'right_sidebar_ad_width')->first()?->value ?? 468,
            'ad_network_domain' => Setting::where('key', 'ad_network_domain')->first()?->value ?? 'www.highperformanceformat.com',
            
            // URLs from the watch-earn settings
            'adsterra_native_url' => Setting::where('key', 'adsterra_native_url')->first()?->value ?? '',
            'adsterra_banner_url' => Setting::where('key', 'adsterra_banner_url')->first()?->value ?? '',
            'adsterra_social_url' => Setting::where('key', 'adsterra_social_url')->first()?->value ?? '',
        ];
        
        return view('admin.global-ads.index', compact('settings'));
    }
    
    /**
     * Show the ad placement guide page.
     */
    public function placementGuide()
    {
        return view('admin.global-ads.placement-guide');
    }
    
    /**
     * Initialize default ad settings.
     */
    public function initialize()
    {
        try {
            // Create default settings if they don't exist
            Setting::updateOrCreate(
                ['key' => 'global_ads_enabled'],
                ['value' => '1'] // Enable by default to make testing easier
            );
            
            Setting::updateOrCreate(
                ['key' => 'global_banner_ad_enabled'],
                ['value' => '0']
            );
            
            Setting::updateOrCreate(
                ['key' => 'global_native_ad_enabled'],
                ['value' => '0']
            );
            
            Setting::updateOrCreate(
                ['key' => 'global_social_ad_enabled'],
                ['value' => '0']
            );
            
            // Explicitly create sidebar settings
            Setting::updateOrCreate(
                ['key' => 'left_sidebar_ad_enabled'],
                ['value' => '1'] // Enable by default for testing
            );
            
            Setting::updateOrCreate(
                ['key' => 'right_sidebar_ad_enabled'],
                ['value' => '0']
            );
            
            Setting::updateOrCreate(
                ['key' => 'global_native_ad_code'],
                ['value' => '']
            );
            
            Setting::updateOrCreate(
                ['key' => 'global_banner_ad_code'],
                ['value' => '']
            );
            
            Setting::updateOrCreate(
                ['key' => 'global_social_ad_code'],
                ['value' => '']
            );
            
            // Set default ad key for left sidebar
            Setting::updateOrCreate(
                ['key' => 'left_sidebar_ad_key'],
                ['value' => 'd007ce8d8904632b8c54dbc4807c29a0']
            );
            
            // Initialize right sidebar ad code
            $defaultAdCode = '<script type="text/javascript">
atOptions = {
    \'key\' : \'d007ce8d8904632b8c54dbc4807c29a0\',
    \'format\' : \'iframe\',
    \'height\' : 60,
    \'width\' : 468,
    \'params\' : {}
};
</script>
<script type="text/javascript" src="//www.highperformanceformat.com/d007ce8d8904632b8c54dbc4807c29a0/invoke.js"></script>';

            Setting::updateOrCreate(
                ['key' => 'right_sidebar_ad_code'],
                ['value' => $defaultAdCode]
            );
            
            // Enable right sidebar by default since we have a default code
            Setting::updateOrCreate(
                ['key' => 'right_sidebar_ad_enabled'],
                ['value' => '1']
            );
            
            \Log::info('Ad settings initialized successfully, including sidebar ads');
            
            return back()->with('success', 'Ad settings initialized successfully. Left sidebar ad enabled with default settings for testing.');
        } catch (\Exception $e) {
            \Log::error('Error initializing ad settings: ' . $e->getMessage());
            return back()->with('error', 'Error initializing settings: ' . $e->getMessage());
        }
    }
    
    /**
     * Update global ads settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'global_banner_ad_code' => 'nullable|string',
            'global_native_ad_code' => 'nullable|string',
            'global_social_ad_code' => 'nullable|string',
            'left_sidebar_ad_key' => 'nullable|string',
            'right_sidebar_ad_code' => 'nullable|string',
        ]);
        
        // Update global ads enabled setting
        Setting::updateOrCreate(
            ['key' => 'global_ads_enabled'],
            ['value' => $request->has('global_ads_enabled') ? '1' : '0']
        );
        
        // Update banner ad settings
        Setting::updateOrCreate(
            ['key' => 'global_banner_ad_enabled'],
            ['value' => $request->has('global_banner_ad_enabled') ? '1' : '0']
        );
        
        Setting::updateOrCreate(
            ['key' => 'global_banner_ad_code'],
            ['value' => $request->global_banner_ad_code]
        );
        
        // Update native ad settings
        Setting::updateOrCreate(
            ['key' => 'global_native_ad_enabled'],
            ['value' => $request->has('global_native_ad_enabled') ? '1' : '0']
        );
        
        Setting::updateOrCreate(
            ['key' => 'global_native_ad_code'],
            ['value' => $request->global_native_ad_code]
        );
        
        // Update social bar ad settings
        Setting::updateOrCreate(
            ['key' => 'global_social_ad_enabled'],
            ['value' => $request->has('global_social_ad_enabled') ? '1' : '0']
        );
        
        Setting::updateOrCreate(
            ['key' => 'global_social_ad_code'],
            ['value' => $request->global_social_ad_code]
        );
        
        // Update left sidebar ad settings
        Setting::updateOrCreate(
            ['key' => 'left_sidebar_ad_enabled'],
            ['value' => $request->has('left_sidebar_ad_enabled') ? '1' : '0']
        );
        
        // Store left sidebar ad key
        Setting::updateOrCreate(
            ['key' => 'left_sidebar_ad_key'],
            ['value' => $request->left_sidebar_ad_key ?: 'd007ce8d8904632b8c54dbc4807c29a0']
        );
        
        // Store complete right sidebar ad code
        Setting::updateOrCreate(
            ['key' => 'right_sidebar_ad_code'],
            ['value' => $request->right_sidebar_ad_code]
        );
        
        // Set right sidebar enabled based on whether code is present
        Setting::updateOrCreate(
            ['key' => 'right_sidebar_ad_enabled'],
            ['value' => !empty($request->right_sidebar_ad_code) ? '1' : '0']
        );
        
        return back()->with('success', 'Global ad settings updated successfully.');
    }
} 