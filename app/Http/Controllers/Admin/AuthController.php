<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Withdraw;
use App\Models\Spin;
use Exception;

class AuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an admin authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show the admin dashboard.
     */
    public function dashboard()
    {
        try {
            // Use direct database queries with error handling for reliability
            $totalUsers = DB::table('users')->count();
            Log::info('User count from DB: ' . $totalUsers);
            
            $activeUsersToday = DB::table('users')
                ->whereDate('last_login_at', now()->toDateString())
                ->count();
                
            $totalSpinsToday = DB::table('spins')
                ->whereDate('created_at', now()->toDateString())
                ->count();
                
            $pendingWithdrawals = DB::table('withdraws')
                ->where('status', 'pending')
                ->count();
            
            $stats = [
                'total_users' => $totalUsers,
                'active_users_today' => $activeUsersToday,
                'total_spins_today' => $totalSpinsToday,
                'pending_withdrawals' => $pendingWithdrawals,
            ];
            
            // Get recent withdrawals with error handling
            try {
                $recent_withdrawals = Withdraw::with('user')
                    ->latest()
                    ->take(5)
                    ->get();
            } catch (Exception $e) {
                Log::error('Error fetching withdrawals: ' . $e->getMessage());
                $recent_withdrawals = collect([]);
            }
    
            // Get recent users with error handling
            try {
                $recent_users = User::latest()
                    ->take(5)
                    ->get();
                
                Log::info('Recent users count: ' . $recent_users->count());
                foreach ($recent_users as $index => $user) {
                    Log::info("User {$index}: ID={$user->id}, Name={$user->name}, Email={$user->email}");
                }
            } catch (Exception $e) {
                Log::error('Error fetching recent users: ' . $e->getMessage());
                $recent_users = collect([]);
            }
    
            return view('admin.dashboard', compact('stats', 'recent_withdrawals', 'recent_users'));
        } catch (Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            
            // Provide fallback data in case of errors
            $stats = [
                'total_users' => 0,
                'active_users_today' => 0,
                'total_spins_today' => 0,
                'pending_withdrawals' => 0,
            ];
            
            return view('admin.dashboard', [
                'stats' => $stats,
                'recent_withdrawals' => collect([]),
                'recent_users' => collect([]),
                'error_message' => 'There was an error loading dashboard data. Please check the logs.'
            ]);
        }
    }

    /**
     * Log the admin out of the application.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
} 