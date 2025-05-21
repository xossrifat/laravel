<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * সাবস্ক্রাইব করার জন্য
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'notify' => 'required',
        ], [
            'notify.required' => 'আপডেট পেতে আপনাকে অবশ্যই বিজ্ঞপ্তি চেকবক্সে টিক দিতে হবে!',
        ]);
        
        // ইউজার লগইন থাকলে আমরা তাকে সাবস্ক্রাইব করবো
        if (Auth::check()) {
            $user = Auth::user();
            $user->update([
                'is_subscribed' => true,
                'notification_preferences' => array_merge((array) $user->notification_preferences, ['feature_updates' => true])
            ]);
            
            return redirect()->back()->with('success', 'আপনি সফলভাবে আপডেট বিজ্ঞপ্তি সক্রিয় করেছেন!');
        }
        
        // ইউজার লগইন না থাকলে সেশনে সেট করবো যাতে ভবিষ্যতে লগইন করলে সেট করা যায়
        session(['subscribe_to_notifications' => true]);
        
        return redirect()->back()->with('success', 'আপনার অনুরোধ নথিভুক্ত করা হয়েছে!');
    }
} 