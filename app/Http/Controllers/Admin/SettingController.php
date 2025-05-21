<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = [
            'coin_rate' => Setting::where('key', 'coin_rate')->first()?->value ?? 1000,
            'min_withdraw_amount' => Setting::where('key', 'min_withdraw_amount')->first()?->value ?? 10,
            'maintenance_mode' => Setting::where('key', 'maintenance_mode')->first()?->value ?? false,
            'app_name' => Setting::where('key', 'app_name')->first()?->value ?? ' RewardBazar',
            'payment_methods' => Setting::where('key', 'payment_methods')->first()?->value ?? '[]',
            'site_title' => Setting::where('key', 'site_title')->first()?->value ?? 'RewardBazar - Earn Rewards',
            'site_description' => Setting::where('key', 'site_description')->first()?->value ?? 'Earn rewards by spinning wheel, watching videos, and completing tasks.',
            'referral_reward' => Setting::where('key', 'referral_reward')->first()?->value ?? 100,
            'referral_percentage' => Setting::where('key', 'referral_percentage')->first()?->value ?? 5,
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update general site settings
     */
    public function updateGeneralSettings(Request $request)
    {
        $request->validate([
            'site_title' => 'required|string|max:255',
            'site_description' => 'required|string|max:500',
        ]);

        try {
            DB::table('settings')->updateOrInsert(
                ['key' => 'site_title'],
                ['key' => 'site_title', 'value' => $request->site_title]
            );
            
            DB::table('settings')->updateOrInsert(
                ['key' => 'site_description'],
                ['key' => 'site_description', 'value' => $request->site_description]
            );
            
            return back()->with('success', 'Site settings updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating site settings: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update site settings: ' . $e->getMessage()]);
        }
    }

    /**
     * Update coin rate.
     */
    public function updateCoinRate(Request $request)
    {
        $request->validate([
            'coin_rate' => 'required|integer|min:1',
        ]);

        try {
            DB::table('settings')->updateOrInsert(
                ['key' => 'coin_rate'],
                ['key' => 'coin_rate', 'value' => $request->coin_rate]
            );
            
            return back()->with('success', 'Coin rate updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating coin rate: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update settings: ' . $e->getMessage()]);
        }
    }

    /**
     * Update redeem conditions.
     */
    public function updateRedeemConditions(Request $request)
    {
        $request->validate([
            'min_withdraw_amount' => 'required|numeric|min:1',
        ]);

        try {
            DB::table('settings')->updateOrInsert(
                ['key' => 'min_withdraw_amount'],
                ['key' => 'min_withdraw_amount', 'value' => $request->min_withdraw_amount]
            );
            
            return back()->with('success', 'Redemption conditions updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating redeem conditions: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update settings: ' . $e->getMessage()]);
        }
    }

    /**
     * Update system limits.
     */
    public function updateLimits(Request $request)
    {
        $request->validate([
            'daily_spin_limit' => 'required|integer|min:1',
            'daily_video_limit' => 'required|integer|min:1',
        ]);

        try {
            DB::table('settings')->updateOrInsert(
                ['key' => 'daily_spin_limit'],
                ['key' => 'daily_spin_limit', 'value' => $request->daily_spin_limit]
            );
            
            DB::table('settings')->updateOrInsert(
                ['key' => 'daily_video_limit'],
                ['key' => 'daily_video_limit', 'value' => $request->daily_video_limit]
            );
            
            return back()->with('success', 'System limits updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating limits: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update settings: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle maintenance mode.
     */
    public function toggleMaintenance(Request $request)
    {
        $request->validate([
            'maintenance_mode' => 'boolean',
        ]);

        try {
            // Get the boolean value from the request
            $maintenanceMode = !empty($request->maintenance_mode);
            
            // Save as boolean to database
            DB::table('settings')
                ->updateOrInsert(
                    ['key' => 'maintenance_mode'],
                    ['value' => $maintenanceMode ? 'true' : 'false']
                );
            
            return back()->with('success', 'Maintenance mode ' . ($maintenanceMode ? 'enabled' : 'disabled') . ' successfully.');
        } catch (\Exception $e) {
            Log::error('Error toggling maintenance mode: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update settings: ' . $e->getMessage()]);
        }
    }

    /**
     * Update referral settings.
     */
    public function updateReferralSettings(Request $request)
    {
        $request->validate([
            'referral_reward' => 'required|integer|min:1',
            'referral_percentage' => 'required|numeric|min:0|max:100',
        ]);

        try {
            DB::table('settings')->updateOrInsert(
                ['key' => 'referral_reward'],
                ['key' => 'referral_reward', 'value' => $request->referral_reward]
            );
            
            DB::table('settings')->updateOrInsert(
                ['key' => 'referral_percentage'],
                ['key' => 'referral_percentage', 'value' => $request->referral_percentage]
            );
            
            return back()->with('success', 'Referral settings updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating referral settings: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update settings: ' . $e->getMessage()]);
        }
    }

    /**
     * Send notification to users.
     */
    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'send_to' => 'required|in:all,active,inactive',
        ]);

        try {
            // Get the target users based on the send_to parameter
            $userQuery = User::query();

            // Filter users
            if ($request->send_to === 'active') {
                $userQuery->whereDate('last_login_at', '>=', now()->subDays(30));
            } elseif ($request->send_to === 'inactive') {
                $userQuery->where(function($query) {
                    $query->whereNull('last_login_at')
                          ->orWhereDate('last_login_at', '<', now()->subDays(30));
                });
            }

            // Get the users
            $users = $userQuery->get();

            // Create notifications for each user
            foreach ($users as $user) {
                $user->notifications()->create([
                    'title' => $request->title,
                    'message' => $request->message,
                    'type' => 'system'
                ]);
            }
            
            $count = $users->count();
            return back()->with('success', "Notification sent to {$count} users successfully.");
        } catch (\Exception $e) {
            Log::error('Error sending notification: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to send notification: ' . $e->getMessage()]);
        }
    }

    /**
     * Update website favicon.
     */
    public function updateFavicon(Request $request)
    {
        $request->validate([
            'favicon' => 'required|image|mimes:png,jpg,jpeg,ico|max:2048',
        ]);

        try {
            if ($request->hasFile('favicon')) {
                $favicon = $request->file('favicon');
                $filename = 'favicon.' . $favicon->getClientOriginalExtension();
                
                // Store in public folder
                $favicon->move(public_path(), $filename);
                
                // Update setting in database
                DB::table('settings')->updateOrInsert(
                    ['key' => 'favicon'],
                    ['key' => 'favicon', 'value' => $filename]
                );
                
                return back()->with('success', 'Favicon updated successfully.');
            }
            
            return back()->withErrors(['favicon' => 'No file was uploaded.']);
        } catch (\Exception $e) {
            Log::error('Error updating favicon: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update favicon: ' . $e->getMessage()]);
        }
    }

    /**
     * Update app name.
     */
    public function updateAppName(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
        ]);

        try {
            DB::table('settings')->updateOrInsert(
                ['key' => 'app_name'],
                ['key' => 'app_name', 'value' => $request->app_name]
            );
            
            return back()->with('success', 'App name updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating app name: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update app name: ' . $e->getMessage()]);
        }
    }
}
