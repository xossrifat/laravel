<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class ShareAdSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Don't share ad settings with admin routes
        if (!$request->is('admin*')) {
            try {
                $adSettings = Setting::getAdSettings();
                
                // Ensure all required keys exist with defaults
                $defaultSettings = [
                    'global_ads_enabled' => false,
                    'global_native_ad_enabled' => false,
                    'global_banner_ad_enabled' => false,
                    'global_social_ad_enabled' => false,
                    'left_sidebar_ad_enabled' => false,
                    'right_sidebar_ad_enabled' => false,
                    'global_native_ad_code' => '',
                    'global_banner_ad_code' => '',
                    'global_social_ad_code' => '',
                    'right_sidebar_ad_code' => '',
                ];
                
                // Merge defaults with any actual settings
                $adSettings = array_merge($defaultSettings, $adSettings);
                
                // Make sure JavaScript code is properly preserved
                foreach (['global_banner_ad_code', 'global_native_ad_code', 'global_social_ad_code', 'right_sidebar_ad_code'] as $codeKey) {
                    if (!empty($adSettings[$codeKey])) {
                        // Ensure script tags are properly preserved
                        $adSettings[$codeKey] = html_entity_decode($adSettings[$codeKey], ENT_QUOTES);
                    }
                }
                
                // Log the sidebar ad settings for debugging
                Log::info('ShareAdSettings middleware: Sidebar ad settings', [
                    'right_enabled' => $adSettings['right_sidebar_ad_enabled'],
                    'right_ad_code_exists' => !empty($adSettings['right_sidebar_ad_code']),
                    'right_ad_code_length' => strlen($adSettings['right_sidebar_ad_code'] ?? '')
                ]);
                
                View::share('adSettings', $adSettings);
            } catch (\Exception $e) {
                Log::error('Error sharing ad settings: ' . $e->getMessage());
                View::share('adSettings', $defaultSettings ?? []);
            }
        }
        
        return $next($request);
    }
} 