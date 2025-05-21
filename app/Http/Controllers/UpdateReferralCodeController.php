<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UpdateReferralCodeController extends Controller
{
    /**
     * Update referral codes for users who don't have one
     */
    public function update()
    {
        // Get all users that don't have a referral code
        $users = User::whereNull('referral_code')->orWhere('referral_code', '')->get();
        
        $count = $users->count();
        if ($count === 0) {
            return redirect()->back()->with('success', 'All users already have referral codes!');
        }
        
        foreach ($users as $user) {
            $user->referral_code = User::generateUniqueReferralCode();
            $user->save();
        }
        
        return redirect()->back()->with('success', "Successfully generated referral codes for {$count} users!");
    }
} 