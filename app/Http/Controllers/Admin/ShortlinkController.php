<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shortlink;
use App\Models\Setting;
use App\Models\User;
use App\Services\EmailService;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ShortlinkController extends Controller
{
    /**
     * Display a listing of all shortlinks.
     */
    public function index()
    {
        $shortlinks = Shortlink::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.shortlinks.index', compact('shortlinks'));
    }

    /**
     * Show the form for creating a new shortlink.
     */
    public function create()
    {
        return view('admin.shortlinks.create');
    }

    /**
     * Store a newly created shortlink in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'coins' => 'required|integer|min:1',
            'active' => 'boolean',
            'rewarded' => 'boolean',
            'daily_reset' => 'boolean',
            'timer_duration' => 'nullable|integer|min:5|max:300',
            'max_claims' => 'nullable|integer|min:1',
            'requires_verification' => 'boolean',
            'verification_code' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.shortlinks.create')
                ->withErrors($validator)
                ->withInput();
        }

        $shortlink = new Shortlink();
        $shortlink->title = $request->title;
        $shortlink->url = $request->url;
        $shortlink->coins = $request->coins;
        $shortlink->active = $request->has('active');
        $shortlink->rewarded = $request->has('rewarded');
        $shortlink->daily_reset = $request->has('daily_reset');
        $shortlink->timer_duration = $request->timer_duration;
        $shortlink->max_claims = $request->filled('max_claims') ? (int)$request->max_claims : null;
        $shortlink->requires_verification = $request->has('requires_verification');
        
        // Set verification code if provided, or generate one if verification is required
        if ($request->filled('verification_code')) {
            $shortlink->verification_code = strtoupper($request->verification_code);
        } elseif ($request->has('requires_verification')) {
            $shortlink->verification_code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        }
        
        $shortlink->save();
        
        // If the shortlink is marked as rewarded, send notification to all users
        if ($request->has('rewarded') && $request->has('active')) {
            $this->sendShortlinkUpdateNotification($shortlink);
        }

        return redirect()->route('admin.shortlinks.index')
            ->with('success', 'Shortlink created successfully.');
    }

    /**
     * Show the form for editing the specified shortlink.
     */
    public function edit($id)
    {
        $shortlink = Shortlink::findOrFail($id);
        return view('admin.shortlinks.edit', compact('shortlink'));
    }

    /**
     * Update the specified shortlink in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'coins' => 'required|integer|min:1',
            'active' => 'boolean',
            'rewarded' => 'boolean',
            'daily_reset' => 'boolean',
            'timer_duration' => 'nullable|integer|min:5|max:300',
            'max_claims' => 'nullable|integer|min:1',
            'requires_verification' => 'boolean',
            'verification_code' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.shortlinks.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        $shortlink = Shortlink::findOrFail($id);
        
        // Save old value of rewarded flag to check if it changed
        $wasRewarded = $shortlink->rewarded;
        
        $shortlink->title = $request->title;
        $shortlink->url = $request->url;
        $shortlink->coins = $request->coins;
        $shortlink->active = $request->has('active');
        $shortlink->rewarded = $request->has('rewarded');
        $shortlink->daily_reset = $request->has('daily_reset');
        $shortlink->timer_duration = $request->timer_duration;
        $shortlink->max_claims = $request->filled('max_claims') ? (int)$request->max_claims : null;
        $shortlink->requires_verification = $request->has('requires_verification');
        
        // Set verification code if provided, or generate one if verification is required and code is empty
        if ($request->filled('verification_code')) {
            $shortlink->verification_code = strtoupper($request->verification_code);
        } elseif ($request->has('requires_verification') && empty($shortlink->verification_code)) {
            $shortlink->verification_code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        }
        
        // If verification is no longer required, clear the code
        if (!$request->has('requires_verification')) {
            $shortlink->verification_code = null;
        }
        
        $shortlink->save();
        
        // If the shortlink is marked as rewarded, send notification to all users
        if ($request->has('rewarded') && $request->has('active')) {
            $this->sendShortlinkUpdateNotification($shortlink);
        }

        return redirect()->route('admin.shortlinks.index')
            ->with('success', 'Shortlink updated successfully.');
    }

    /**
     * Toggle the active status of the specified shortlink.
     */
    public function toggle($id)
    {
        $shortlink = Shortlink::findOrFail($id);
        $shortlink->active = !$shortlink->active;
        $shortlink->save();

        return redirect()->route('admin.shortlinks.index')
            ->with('success', 'Shortlink status updated successfully.');
    }

    /**
     * Remove the specified shortlink from storage.
     */
    public function destroy($id)
    {
        $shortlink = Shortlink::findOrFail($id);
        $shortlink->delete();

        return redirect()->route('admin.shortlinks.index')
            ->with('success', 'Shortlink deleted successfully.');
    }
    
    /**
     * Display analytics about shortlink usage.
     */
    public function analytics()
    {
        $shortlinks = Shortlink::withCount('users')->orderByDesc('users_count')->get();
        
        $totalClaims = 0;
        foreach ($shortlinks as $shortlink) {
            $totalClaims += $shortlink->users_count;
        }
        
        return view('admin.shortlinks.analytics', compact('shortlinks', 'totalClaims'));
    }
    
    /**
     * Show the shortlinks configuration page.
     */
    public function config()
    {
        $claimTimeout = (int) Setting::get('shortlink_claim_timeout', 15);
        $showTimer = (bool) Setting::get('shortlink_show_timer', true);
        return view('admin.shortlinks.config', compact('claimTimeout', 'showTimer'));
    }
    
    /**
     * Update shortlinks configuration.
     */
    public function updateConfig(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'claim_timeout' => 'required|integer|min:5|max:300',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.shortlinks.config')
                ->withErrors($validator)
                ->withInput();
        }

        Setting::set('shortlink_claim_timeout', $request->claim_timeout);
        Setting::set('shortlink_show_timer', $request->has('show_timer'));

        return redirect()->route('admin.shortlinks.config')
            ->with('success', 'Shortlink configuration updated successfully.');
    }

    /**
     * Send notification to all users about the updated shortlink
     */
    private function sendShortlinkUpdateNotification(Shortlink $shortlink)
    {
        try {
            // Find the template
            $template = EmailTemplate::where('slug', 'shortlink-update')
                ->where('active', true)
                ->first();
                
            if (!$template) {
                Log::error("Email template 'shortlink-update' not found or not active.");
                return;
            }
            
            // Get all users with email addresses
            $users = User::whereNotNull('email')->get();
            
            if ($users->isEmpty()) {
                return;
            }
            
            $emailService = app(EmailService::class);
            
            // Send emails to each user
            foreach ($users as $user) {
                // Set up variables for email template
                $emailVars = [
                    'userName' => $user->name,
                    'shortlinkName' => $shortlink->title,
                    'rewardAmount' => $shortlink->coins,
                    'rewardCurrency' => 'coins',
                    'shortlinksLink' => route('shortlinks.index')
                ];
                
                // Send email
                $emailService->sendTemplateEmail(
                    $user->email,
                    'shortlink-update',
                    $emailVars
                );
            }
        } catch (\Exception $e) {
            Log::error('Failed to send shortlink update notification: ' . $e->getMessage());
        }
    }
}
