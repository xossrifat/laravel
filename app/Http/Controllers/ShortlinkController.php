<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shortlink;
use App\Models\EmailTemplate;
use App\Services\EmailService;
use App\Services\ReferralService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ShortlinkController extends Controller
{
    /**
     * Display a listing of all available shortlinks.
     */
    public function index()
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            // Redirect to login page with a message
            return redirect()->route('login')->with('message', 'Please login to use the Shortlinks feature.');
        }

        $shortlinks = Shortlink::where('active', true)->get();
        $user = Auth::user();
        
        // Process each shortlink to add view-specific attributes
        foreach ($shortlinks as $shortlink) {
            // Check if user has already claimed this shortlink (respecting daily_reset setting)
            $shortlink->already_claimed = $shortlink->hasUserClaimed($user->id) && $shortlink->hasUserVerified($user->id);
            
            // Generate verification code if needed
            if ($shortlink->requires_verification && !$shortlink->verification_code) {
                $shortlink->generateVerificationCode();
            }
            
            // Set additional view-only properties
            $shortlink->claim_count = $shortlink->claimCount();
            $shortlink->claims_left = $shortlink->max_claims !== null ? max(0, $shortlink->max_claims - $shortlink->claim_count) : null;
        }
        
        // Get the claim timeout from settings or use 15 seconds as default
        $claimTimeout = (int) \App\Models\Setting::get('shortlink_claim_timeout', 15);
        
        // Get timer visibility setting
        $showTimer = (bool) \App\Models\Setting::get('shortlink_show_timer', true);
        
        return view('shortlinks.index', compact('shortlinks', 'claimTimeout', 'showTimer'));
    }
    
    /**
     * Complete a shortlink and reward the user with coins.
     */
    public function complete(Request $request, $id)
    {
        $shortlink = Shortlink::findOrFail($id);
        $user = Auth::user();
        
        // Check if user has already claimed this shortlink (respecting daily_reset setting)
        if ($shortlink->hasUserClaimed($user->id) && $shortlink->hasUserVerified($user->id)) {
            $message = $shortlink->daily_reset ? 
                'You have already claimed this shortlink today.' : 
                'You have already claimed this shortlink before.';
                
                return redirect()->route('shortlinks.index')
                ->with('error', $message);
        }
        
        // Check if claim limit is reached
        if ($shortlink->isClaimLimitReached()) {
            return redirect()->route('shortlinks.index')
                    ->with('error', 'This shortlink has reached its maximum number of claims.');
        }
        
        // Add user to the shortlink with verified status
        $user->shortlinks()->attach($shortlink->id, [
            'last_claimed_at' => now(),
            'verified' => true
        ]);
        
        // Add coins to user's balance
        $user->addCoins($shortlink->coins);
        
        // Process referral percentage earnings
        app(ReferralService::class)->processPercentageReward($user, $shortlink->coins, 'shortlink');
        
        // Send reward email if shortlink is set to rewarded
        $this->sendRewardEmail($user, $shortlink);
        
        return redirect()->route('shortlinks.index')
                ->with('success', "Congrats! You earned {$shortlink->coins} coins.");
    }
    
    /**
     * Send reward email
     */
    private function sendRewardEmail($user, $shortlink)
    {
        if (!$shortlink->rewarded) {
            return;
        }
        
            try {
                // Find the shortlink reward email template
                $template = EmailTemplate::where('slug', 'shortlink-reward')->first();
                
                if ($template && $template->active) {
                    // Setup variables for the email
                    $emailVars = [
                        'userName' => $user->name,
                        'shortlinkName' => $shortlink->title,
                        'rewardAmount' => $shortlink->coins,
                        'rewardCurrency' => 'coins',
                        'completionDate' => now()->format('Y-m-d H:i:s'),
                        'shortlinksLink' => route('shortlinks.index')
                    ];
                    
                    // Send the email
                    app(EmailService::class)->sendTemplateEmail(
                        $user->email,
                        'shortlink-reward',
                        $emailVars
                    );
                }
            } catch (\Exception $e) {
                // Just log the error but don't disrupt the process
                \Log::error('Failed to send shortlink reward email: ' . $e->getMessage());
            }
    }
}
