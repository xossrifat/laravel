<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdsController extends Controller
{
    public function show()
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            // Redirect to login page with a message
            return redirect()->route('login')->with('message', 'Please login to use the Video Ads feature.');
        }

        // ... rest of the original method ...
    }
} 