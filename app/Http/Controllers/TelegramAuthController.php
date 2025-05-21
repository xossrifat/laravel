<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Services\ReferralService;

class TelegramAuthController extends Controller
{
    protected $telegramService;
    
    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }
    
    /**
     * Handle the telegram login request
     */
    public function login(Request $request)
    {
        // Log the request
        Log::info('Telegram login attempt', [
            'request_type' => 'Telegram Mini App Auth',
            'has_init_data' => $request->has('initData'),
            'user_agent' => $request->header('User-Agent'),
            'referral_code_in_session' => session('telegram_referral_code'),
            'referral_code_in_request' => $request->input('referral_code')
        ]);
        
        // Validate request contains initData
        $validated = $request->validate([
            'initData' => 'required|string',
            'referral_code' => 'nullable|string'
        ]);
        
        // Check for referral code from request and store in session if available
        if (!empty($validated['referral_code'])) {
            $referralCode = $validated['referral_code'];
            session(['telegram_referral_code' => $referralCode]);
            cookie()->queue('telegram_referral_code', $referralCode, 60); // 1 hour
            
            Log::info('Stored referral code from Mini App request', [
                'referral_code' => $referralCode,
                'session_id' => session()->getId()
            ]);
        }
        
        // Parse and validate Telegram data
        $userData = $this->telegramService->parseInitData($validated['initData']);
        
        if (!$userData || !isset($userData['id'])) {
            Log::warning('Invalid Telegram data received', [
                'init_data' => substr($validated['initData'], 0, 100) // Log partial data for debugging
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Invalid Telegram data'
            ], 400);
        }
        
        // Log successful data parse
        Log::info('Telegram data validated successfully', [
            'telegram_id' => $userData['id'],
            'username' => $userData['username'] ?? null,
        ]);
        
        try {
            // Check if this is a new or existing user
            $isNewUser = !User::where('telegram_id', $userData['id'])->exists();
            
            // Find or create user
            $user = $this->findOrCreateUser($userData);
            
            // Update last login timestamp
            $user->last_login_at = Carbon::now();
            $user->save();
            
            // Login user
            Auth::login($user, true);
            
            // Determine redirect URL based on available routes
            $redirectUrl = '/';
            if (Route::has('home')) {
                $redirectUrl = route('home');
            } elseif (Route::has('dashboard')) {
                $redirectUrl = route('dashboard');
            }
            
            return response()->json([
                'success' => true,
                'message' => $isNewUser ? 'Account created and logged in successfully' : 'Logged in successfully',
                'redirect' => $redirectUrl,
                'is_new_user' => $isNewUser,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'telegram_id' => $user->telegram_id,
                    'coins' => $user->coins
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Telegram login error: ' . $e->getMessage(), [
                'telegram_id' => $userData['id'] ?? null,
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error processing login: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Handle telegram bot start command with referral code
     */
    public function handleStartCommand(Request $request)
    {
        // Enhanced logging with all details
        Log::debug('Telegram start command details', [
            'all_request_params' => $request->all(),
            'start_param' => $request->get('start', ''),
            'startapp_param' => $request->get('startapp', ''),
            'user_agent' => $request->header('User-Agent'),
            'session_id' => session()->getId(),
            'referer' => $request->header('referer'),
            'url' => $request->url(),
            'full_url' => $request->fullUrl(),
        ]);
        
        // Extract parameters based on URL format
        $startParam = $request->get('start', '');
        $startAppParam = $request->get('startapp', '');
        
        // Check for multiple referral code formats
        $referralCode = null;
        
        // Check Mini App format: startapp=referral_CODE
        if (!empty($startAppParam)) {
            if (strpos($startAppParam, 'referral_') === 0) {
                $referralCode = substr($startAppParam, 9); // Remove 'referral_' prefix
                Log::info('Found referral in startapp parameter', [
                    'startapp_param' => $startAppParam,
                    'referral_code' => $referralCode,
                    'extraction_method' => 'startapp prefix match'
                ]);
            } else {
                Log::warning('Unexpected startapp parameter format', [
                    'startapp_param' => $startAppParam
                ]);
            }
        }
        // Check classic bot format: start=ref_CODE
        elseif (!empty($startParam)) {
            if (strpos($startParam, 'ref_') === 0) {
                $referralCode = substr($startParam, 4); // Remove 'ref_' prefix
                Log::info('Found referral in start parameter', [
                    'start_param' => $startParam, 
                    'referral_code' => $referralCode,
                    'extraction_method' => 'start prefix match'
                ]);
            } else {
                Log::warning('Unexpected start parameter format', [
                    'start_param' => $startParam
                ]);
            }
        }
        
        // Additional check: try to extract from URL path directly in case parameters weren't properly parsed
        $path = $request->path();
        if (empty($referralCode) && preg_match('/referral_([A-Z0-9]+)/i', $path, $matches)) {
            $referralCode = $matches[1];
            Log::info('Found referral code in URL path', [
                'path' => $path,
                'referral_code' => $referralCode,
                'extraction_method' => 'path regex match'
            ]);
        }
        
        // Store referral code if found
        if ($referralCode) {
            session(['telegram_referral_code' => $referralCode]);
            Log::info('Stored referral code in session', [
                'referral_code' => $referralCode,
                'session_id' => session()->getId(),
                'session_contents' => array_keys(session()->all())
            ]);
            
            // Flash message so user knows they came from referral
            session()->flash('referral_message', 'You were invited by a friend! Sign up to give them a reward.');
            
            // Additionally store in cookies as fallback
            cookie()->queue('telegram_referral_code', $referralCode, 60); // 1 hour
        } else {
            Log::warning('No referral code found in request parameters', [
                'request_path' => $request->path(),
                'request_url' => $request->url(),
                'query_string' => $request->getQueryString()
            ]);
        }
        
        // Redirect to Telegram auth page
        return redirect()->route('telegram.auth');
    }
    
    /**
     * Handle direct referral links from Telegram Mini App
     */
    public function handleReferral($code)
    {
        // Log the request with detailed information
        Log::info('Telegram direct referral link accessed', [
            'referral_code' => $code,
            'session_id' => session()->getId(),
            'previous_url' => url()->previous()
        ]);
        
        // Store referral code in session for later use during registration
        session(['telegram_referral_code' => $code]);
        
        // Flash a message about the referral
        session()->flash('referral_message', 'You were invited by a friend! Sign up to give them a reward.');
        
        // Redirect to Telegram auth page
        return redirect()->route('telegram.auth');
    }
    
    /**
     * Find existing user or create a new one
     */
    protected function findOrCreateUser(array $userData): User
    {
        // Find by telegram_id
        $user = User::where('telegram_id', $userData['id'])->first();
        
        // If user exists, update telegram username if it changed
        if ($user) {
            $updates = [];
            
            if (isset($userData['username']) && $user->telegram_username !== $userData['username']) {
                $updates['telegram_username'] = $userData['username'];
            }
            
            // Update name if changed from Telegram
            $newName = $this->formatName($userData);
            if ($newName && $user->name !== $newName && $user->is_telegram_user) {
                // Only update name for pure Telegram users, not manual registrations
                $updates['name'] = $newName;
            }
            
            if (!empty($updates)) {
                $user->update($updates);
            }
            
            return $user;
        }

        // Check if we have phone number from Telegram data
        $phoneNumber = null;
        if (isset($userData['phone_number']) && !empty($userData['phone_number'])) {
            $phoneNumber = $userData['phone_number'];
            
            // Check if the phone number is already registered
            $existingUserWithPhone = User::where('mobile_number', $phoneNumber)->first();
            if ($existingUserWithPhone) {
                // Instead of creating a new user, update the existing user with Telegram info
                $existingUserWithPhone->telegram_id = $userData['id'];
                $existingUserWithPhone->telegram_username = $userData['username'] ?? null;
                $existingUserWithPhone->is_mobile_verified = true;  // Auto-verify since we got it from Telegram
                $existingUserWithPhone->mobile_verified_at = now();
                $existingUserWithPhone->save();
                
                Log::info('Linked existing user account with Telegram', [
                    'user_id' => $existingUserWithPhone->id,
                    'telegram_id' => $userData['id'],
                    'mobile_number' => $phoneNumber
                ]);
                
                return $existingUserWithPhone;
            }
        }
        
        // Otherwise create new user
        $name = $this->formatName($userData);
        $username = $userData['username'] ?? null;
        
        // Create a unique email using telegram ID
        $email = 'tg_' . $userData['id'] . '@example.com';
        
        // Create random password
        $password = Hash::make(Str::random(16));
        
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'telegram_id' => $userData['id'],
            'telegram_username' => $username,
            'is_telegram_user' => true,
            'coins' => 0, // Start with 0 coins
            'referral_code' => User::generateUniqueReferralCode(),
            'mobile_number' => $phoneNumber, // Save mobile number if available
            'is_mobile_verified' => $phoneNumber ? true : false, // Auto-verify if we have phone from Telegram
            'mobile_verified_at' => $phoneNumber ? now() : null,
        ]);
        
        Log::info('New user created via Telegram', [
            'user_id' => $user->id,
            'telegram_id' => $userData['id'],
            'telegram_username' => $username,
            'has_phone' => $phoneNumber ? 'yes' : 'no'
        ]);
        
        // Check for referral code in multiple places
        $referralCode = session('telegram_referral_code');
        $cookieReferralCode = request()->cookie('telegram_referral_code');
        
        // Log all possible referral code sources
        Log::info('Checking referral codes for new user', [
            'user_id' => $user->id,
            'session_referral_code' => $referralCode,
            'cookie_referral_code' => $cookieReferralCode,
            'session_id' => session()->getId(),
            'session_data_keys' => array_keys(session()->all()),
        ]);
        
        // Use cookie as fallback if session is empty
        if (!$referralCode && $cookieReferralCode) {
            $referralCode = $cookieReferralCode;
            Log::info('Using referral code from cookie instead of session', [
                'referral_code' => $referralCode
            ]);
        }
        
        if ($referralCode) {
            Log::info('Processing referral for new user', [
                'user_id' => $user->id,
                'referral_code' => $referralCode
            ]);
            
            $result = app(ReferralService::class)->processInitialReferral($user, $referralCode);
            
            if ($result) {
                Log::info('Referral processed successfully', [
                    'user_id' => $user->id,
                    'referral_code' => $referralCode,
                    'result' => $result
                ]);
            } else {
                Log::warning('Referral processing failed', [
                    'user_id' => $user->id,
                    'referral_code' => $referralCode
                ]);
            }
            
            // Clear the referral code from both session and cookie
            session()->forget('telegram_referral_code');
            cookie()->queue(cookie()->forget('telegram_referral_code'));
            Log::info('Cleared referral codes from session and cookie');
        } else {
            Log::info('No referral code found for new user', [
                'user_id' => $user->id
            ]);
        }
        
        return $user;
    }
    
    /**
     * Format the user's name from Telegram data
     */
    private function formatName(array $userData): string
    {
        $name = $userData['first_name'] ?? '';
        if (isset($userData['last_name']) && !empty($userData['last_name'])) {
            $name .= ' ' . $userData['last_name'];
        }
        
        // If no name is available, use username or fallback
        if (empty(trim($name))) {
            $name = $userData['username'] ?? 'Telegram User';
        }
        
        return trim($name);
    }
    
    /**
     * Handle referrals directly from Telegram Mini App format
     * This endpoint can be accessed via: /telegram/miniapp-ref/{code}
     */
    public function handleMiniAppReferral(Request $request, $code = null)
    {
        // Check if code is provided in the URL
        if (empty($code)) {
            // Try to extract from request parameters
            $startappParam = $request->get('startapp', '');
            if (strpos($startappParam, 'referral_') === 0) {
                $code = substr($startappParam, 9); // Remove 'referral_' prefix
            }
        }
        
        Log::info('Telegram Mini App referral endpoint accessed', [
            'provided_code' => $code,
            'request_path' => $request->path(),
            'startapp_param' => $request->get('startapp', ''),
            'all_params' => $request->all(),
            'user_agent' => $request->header('User-Agent')
        ]);
        
        if ($code) {
            // Store in both session and cookie
            session(['telegram_referral_code' => $code]);
            cookie()->queue('telegram_referral_code', $code, 60); // 1 hour
            
            // Flash a message about the referral
            session()->flash('referral_message', 'You were invited by a friend! Sign up to give them a reward.');
            
            Log::info('Mini App referral code stored successfully', [
                'referral_code' => $code,
                'session_id' => session()->getId()
            ]);
        } else {
            Log::warning('Mini App referral called with no valid code');
        }
        
        // Redirect to Telegram auth page
        return redirect()->route('telegram.auth');
    }
}