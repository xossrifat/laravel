<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ReferralController;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        // Get referral code from query parameter
        $referralCode = $request->query('ref');
        
        return view('auth.register', compact('referralCode'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'mobile_number' => ['required', 'string', 'regex:/^[0-9+\s]+$/', 'unique:'.User::class],
            'preferred_otp_channel' => ['required', 'in:sms,whatsapp'],
            'referral_code' => ['nullable', 'string', 'max:20'],
        ]);
        
        // Check if mobile number is already linked to another account
        // This is a double-check in addition to the 'unique' validation
        $existingUser = User::where('mobile_number', $request->mobile_number)->first();
        if ($existingUser) {
            return back()->withErrors([
                'mobile_number' => 'This mobile number is already registered with another account.'
            ])->withInput();
        }

        // Check if user with same email is already registered via Telegram
        $telegramUser = User::where('email', $request->email)
                            ->where('is_telegram_user', true)
                            ->first();
        
        if ($telegramUser) {
            return back()->withErrors([
                'email' => 'This email is already linked to a Telegram account. Please login with Telegram instead.'
            ])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'referral_code' => User::generateUniqueReferralCode(),
            'mobile_number' => $request->mobile_number,
            'preferred_otp_channel' => $request->preferred_otp_channel,
            'is_mobile_verified' => false, // Mark as unverified initially
        ]);

        // Process referral if code was provided
        if ($request->filled('referral_code')) {
            app(ReferralController::class)->processReferral($user, $request->referral_code);
        }

        event(new Registered($user));

        Auth::login($user);
        
        // Redirect to mobile verification page instead of dashboard
        return redirect(route('verification.mobile.verify', absolute: false));
    }
}
