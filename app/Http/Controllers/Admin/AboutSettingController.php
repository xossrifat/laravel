<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutSettingController extends Controller
{
    /**
     * Display the about settings form.
     */
    public function index()
    {
        $settings = AboutSetting::getCurrentSettings();
        return view('admin.about-settings.index', compact('settings'));
    }

    /**
     * Update the general about information
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_version' => 'required|string|max:20',
            'app_tagline' => 'required|string|max:255',
            'app_description' => 'required|string',
        ]);

        $settings = AboutSetting::getCurrentSettings();
        
        // Update the settings
        $settings->update([
            'app_name' => $request->app_name,
            'app_version' => $request->app_version,
            'app_tagline' => $request->app_tagline,
            'app_description' => $request->app_description,
        ]);

        // Handle logo upload if provided
        if ($request->hasFile('logo')) {
            $request->validate([
                'logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Delete old logo if exists
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }

            // Store new logo
            $logoPath = $request->file('logo')->store('logos', 'public');
            $settings->update(['logo_path' => $logoPath]);
        }

        return back()->with('success', 'General app information updated successfully.');
    }

    /**
     * Update the features list
     */
    public function updateFeatures(Request $request)
    {
        $request->validate([
            'features' => 'required|array',
            'features.*.title' => 'required|string|max:100',
            'features.*.description' => 'required|string',
            'features.*.icon' => 'required|string|max:50',
        ]);

        $settings = AboutSetting::getCurrentSettings();
        
        $settings->update([
            'features_json' => $request->features,
        ]);

        return back()->with('success', 'Features updated successfully.');
    }

    /**
     * Update support information
     */
    public function updateSupport(Request $request)
    {
        $request->validate([
            'support_email' => 'required|email|max:255',
            'live_chat_available' => 'boolean',
        ]);

        $settings = AboutSetting::getCurrentSettings();
        
        $settings->update([
            'support_email' => $request->support_email,
            'live_chat_available' => $request->has('live_chat_available'),
        ]);

        return back()->with('success', 'Support information updated successfully.');
    }

    /**
     * Update legal links
     */
    public function updateLegal(Request $request)
    {
        $request->validate([
            'terms_url' => 'nullable|url|max:255',
            'privacy_url' => 'nullable|url|max:255',
            'cookie_url' => 'nullable|url|max:255',
        ]);

        $settings = AboutSetting::getCurrentSettings();
        
        $settings->update([
            'terms_url' => $request->terms_url,
            'privacy_url' => $request->privacy_url,
            'cookie_url' => $request->cookie_url,
        ]);

        return back()->with('success', 'Legal links updated successfully.');
    }
} 