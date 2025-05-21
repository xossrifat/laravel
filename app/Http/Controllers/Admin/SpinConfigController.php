<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpinReward;
use App\Models\Spin;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class SpinConfigController extends Controller
{
    /**
     * Show the spin configuration page.
     */
    public function index()
    {
        $rewards = SpinReward::all();
        $dailyLimit = Setting::where('key', 'daily_spin_limit')->first()?->value ?? 10;
        
        // Get spin ads settings
        $spinAdsEnabled = Setting::where('key', 'spin_ads_enabled')->first()?->value ?? false;
        $spinAdUrls = Setting::getJson('spin_ad_urls', []);
        
        return view('admin.spin-config.index', compact('rewards', 'dailyLimit', 'spinAdsEnabled', 'spinAdUrls'));
    }

    /**
     * Update spin rewards.
     */
    public function updateRewards(Request $request)
    {
        $request->validate([
            'rewards' => 'required|array',
            'rewards.*.coins' => 'required|integer|min:0',
            'rewards.*.label' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            
            // Use delete() instead of truncate() to respect foreign key constraints
            SpinReward::query()->delete();
            
            // Create new rewards
            foreach ($request->rewards as $reward) {
                SpinReward::create([
                    'coins' => $reward['coins'],
                    'label' => $reward['label'],
                    'probability' => $reward['probability'] ?? 0
                ]);
            }
            
            DB::commit();
            return back()->with('success', 'Spin rewards updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update rewards: ' . $e->getMessage()]);
        }
    }

    /**
     * Update reward probabilities.
     */
    public function updateProbability(Request $request)
    {
        $request->validate([
            'probabilities' => 'required|array',
            'probabilities.*' => 'required|numeric|min:0|max:100',
        ]);

        // Ensure probabilities sum to 100
        $total = array_sum($request->probabilities);
        if ($total != 100) {
            return back()->withErrors(['probability' => 'Probabilities must sum to 100%.']);
        }

        // Update probabilities
        foreach ($request->probabilities as $id => $probability) {
            SpinReward::findOrFail($id)->update(['probability' => $probability]);
        }

        return back()->with('success', 'Probabilities updated successfully.');
    }

    /**
     * Update spin limits.
     */
    public function updateLimits(Request $request)
    {
        $request->validate([
            'daily_limit' => 'required|integer|min:1',
        ]);

        Setting::updateOrCreate(
            ['key' => 'daily_spin_limit'],
            ['value' => $request->daily_limit]
        );

        return back()->with('success', 'Spin limits updated successfully.');
    }
    
    /**
     * Update spin ads settings.
     */
    public function updateAds(Request $request)
    {
        // Update spin ads enabled setting
        Setting::updateOrCreate(
            ['key' => 'spin_ads_enabled'],
            ['value' => $request->has('spin_ads_enabled') ? '1' : '0']
        );
        
        // Filter out empty URLs
        $adUrls = array_filter($request->spin_ad_urls ?? [], function($url) {
            return !empty(trim($url));
        });
        
        // Store ad URLs as JSON
        Setting::updateOrCreate(
            ['key' => 'spin_ad_urls'],
            ['value' => json_encode(array_values($adUrls))]
        );
        
        return back()->with('success', 'Spin ads settings updated successfully.');
    }
} 