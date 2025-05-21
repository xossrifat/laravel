<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function settings()
    {
        $user = Auth::user();
        return view('profile.settings', compact('user'));
    }

    public function userSettings()
    {
        $user = Auth::user();
        return view('user.settings', compact('user'));
    }

    // Individual settings pages
    public function accountSettings()
    {
        $user = Auth::user();
        return view('user.settings.account', compact('user'));
    }

    public function notificationSettings()
    {
        $user = Auth::user();
        return view('user.settings.notifications', compact('user'));
    }

    public function appearanceSettings()
    {
        $user = Auth::user();
        return view('user.settings.appearance', compact('user'));
    }

    public function privacySettings()
    {
        $user = Auth::user();
        return view('user.settings.privacy', compact('user'));
    }

    public function aboutSettings()
    {
        $user = Auth::user();
        return view('user.settings.about', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update($validated);

        return redirect()->route('user.settings.account')->with('success', 'Profile updated successfully');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('user.settings.account')->with('success', 'Password updated successfully');
    }

    public function updateTheme(Request $request)
    {
        try {
            $validated = $request->validate([
                'theme' => ['required', 'string', 'in:light,dark,system'],
            ]);

            $user = Auth::user();
            
            // Log the theme preference update for debugging
            \Log::info('Theme preference update attempt for user ' . $user->id, [
                'current_theme' => $user->theme,
                'new_theme' => $validated['theme'],
                'is_ajax' => $request->ajax(),
                'wants_json' => $request->wantsJson(),
                'content_type' => $request->header('Content-Type')
            ]);
            
            // Update the theme preference
            $user->update([
                'theme' => $validated['theme'],
            ]);
            
            // Verify that the update succeeded
            $user->refresh();
            \Log::info('Theme preference updated successfully', [
                'user_id' => $user->id,
                'updated_theme' => $user->theme,
            ]);

            // Return appropriate response based on request type
            if ($request->wantsJson() || $request->ajax() || $request->header('Content-Type') === 'application/json') {
                return response()->json([
                    'success' => true,
                    'message' => 'Theme preference updated',
                    'theme' => $user->theme
                ]);
            }
            
            return redirect()->route('user.settings.appearance')->with('success', 'Theme preference updated');
        } catch (\Exception $e) {
            \Log::error('Error updating theme preference: ' . $e->getMessage());
            
            if ($request->wantsJson() || $request->ajax() || $request->header('Content-Type') === 'application/json') {
                return response()->json([
                    'success' => false,
                    'message' => 'There was a problem updating your theme preference'
                ], 500);
            }
            
            return redirect()->route('user.settings.appearance')->with('error', 'There was a problem updating your theme preference. Please try again.');
        }
    }

    public function updateNotifications(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Update user preferences
            $user->update([
                'notify_rewards' => $request->has('notify_rewards'),
                'notify_updates' => $request->has('notify_updates'),
                'notify_withdrawals' => $request->has('notify_withdrawals'),
            ]);
            
            // Log successful update for debugging
            \Log::info('Notification preferences updated for user: ' . $user->id, [
                'notify_rewards' => $request->has('notify_rewards'),
                'notify_updates' => $request->has('notify_updates'),
                'notify_withdrawals' => $request->has('notify_withdrawals'),
            ]);
            
            return redirect()->route('user.settings.notifications')->with('success', 'Notification preferences updated');
        } catch (\Exception $e) {
            \Log::error('Error updating notification preferences: ' . $e->getMessage());
            return redirect()->route('user.settings.notifications')->with('error', 'There was a problem updating your notification preferences. Please try again.');
        }
    }
    
    public function updatePayout(Request $request)
    {
        try {
            $validated = $request->validate([
                'payout_email' => ['required', 'email', 'max:255'],
            ]);

            $user = Auth::user();
            $user->update([
                'payout_email' => $validated['payout_email'],
            ]);
            
            \Log::info('Payout email updated for user: ' . $user->id, [
                'payout_email' => $validated['payout_email']
            ]);
            
            return redirect()->route('user.settings.account')->with('success', 'Payout information updated successfully');
        } catch (\Exception $e) {
            \Log::error('Error updating payout email: ' . $e->getMessage());
            return redirect()->route('user.settings.account')->with('error', 'There was a problem updating your payout information. Please try again.');
        }
    }

    public function deleteAccount()
    {
        try {
            $user = Auth::user();
            
            // Log account deletion attempt
            \Log::info('Account deletion requested for user: ' . $user->id);
            
            // Store user ID for logging
            $userId = $user->id;
            
            // Logout the user
            Auth::logout();
            
            // Delete the user account
            $user->delete();
            
            // Log successful deletion
            \Log::info('Account deleted successfully for user: ' . $userId);
            
            // Redirect to welcome page with success message
            return redirect()->route('welcome')->with('success', 'Your account has been deleted successfully.');
            
        } catch (\Exception $e) {
            \Log::error('Error deleting account: ' . $e->getMessage());
            return redirect()->route('user.settings.account')->with('error', 'There was a problem deleting your account. Please try again.');
        }
    }
}
