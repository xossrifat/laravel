<?php

namespace App\Http\Controllers;

use App\Models\AboutSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    // Main settings page (redirects to account)
    public function index()
    {
        return redirect()->route('user.settings.account');
    }

    // Individual settings pages
    public function account()
    {
        $user = Auth::user();
        return view('user.settings.account', compact('user'));
    }

    public function notifications()
    {
        $user = Auth::user();
        return view('user.settings.notifications', compact('user'));
    }

    public function appearance()
    {
        $user = Auth::user();
        return view('user.settings.appearance', compact('user'));
    }

    public function privacy()
    {
        $user = Auth::user();
        return view('user.settings.privacy', compact('user'));
    }

    public function about()
    {
        $user = Auth::user();
        $aboutSettings = AboutSetting::getCurrentSettings();
        return view('user.settings.about', compact('user', 'aboutSettings'));
    }
} 