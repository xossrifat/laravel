<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TelegramUserController extends Controller
{
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    /**
     * Display the Telegram users dashboard
     */
    public function dashboard()
    {
        // Get basic stats
        $totalTelegramUsers = User::whereNotNull('telegram_id')->count();
        $totalUsers = User::count();
        $telegramPercentage = $totalUsers > 0 ? round(($totalTelegramUsers / $totalUsers) * 100) : 0;
        
        // Get active users stats
        $lastWeekActive = User::whereNotNull('telegram_id')
            ->where('last_login_at', '>=', Carbon::now()->subDays(7))
            ->count();
        
        // Get new users trends
        $newUsersDaily = $this->getNewUsersTrend('daily', 7);
        $newUsersWeekly = $this->getNewUsersTrend('weekly', 10);
        $newUsersMonthly = $this->getNewUsersTrend('monthly', 6);
        
        // Get top earning Telegram users
        $topEarners = User::whereNotNull('telegram_id')
            ->orderBy('coins', 'desc')
            ->limit(5)
            ->get(['id', 'name', 'telegram_username', 'telegram_id', 'coins', 'last_login_at']);
        
        return view('admin.telegram.dashboard', [
            'totalTelegramUsers' => $totalTelegramUsers,
            'totalUsers' => $totalUsers,
            'telegramPercentage' => $telegramPercentage,
            'lastWeekActive' => $lastWeekActive,
            'newUsersDaily' => $newUsersDaily,
            'newUsersWeekly' => $newUsersWeekly,
            'newUsersMonthly' => $newUsersMonthly,
            'topEarners' => $topEarners,
        ]);
    }
    
    /**
     * List all Telegram users
     */
    public function index(Request $request)
    {
        $query = User::whereNotNull('telegram_id');
        
        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('last_login_at', '>=', Carbon::now()->subDays(7));
            } elseif ($request->status === 'inactive') {
                $query->where(function($q) {
                    $q->whereNull('last_login_at')
                      ->orWhere('last_login_at', '<', Carbon::now()->subDays(7));
                });
            } elseif ($request->status === 'banned') {
                $query->where('is_banned', true);
            }
        }
        
        // Filter by search term
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('telegram_username', 'like', "%{$search}%")
                  ->orWhere('telegram_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Sort
        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction ?? 'desc';
        $query->orderBy($sort, $direction);
        
        $users = $query->paginate(20)->withQueryString();
        
        return view('admin.telegram.users', [
            'users' => $users,
            'sort' => $sort,
            'direction' => $direction,
            'filters' => $request->only(['status', 'search']),
        ]);
    }
    
    /**
     * Show details for a specific Telegram user
     */
    public function show(User $user)
    {
        if (is_null($user->telegram_id)) {
            return redirect()->route('admin.telegram.users')
                ->with('error', 'This user is not connected to Telegram.');
        }
        
        // Get user activities
        $spins = $user->spins()->latest()->take(10)->get();
        $videoWatches = $user->videoWatches()->latest()->take(10)->get();
        $withdrawals = $user->withdrawals()->latest()->take(10)->get();
        
        return view('admin.telegram.user-detail', [
            'user' => $user,
            'spins' => $spins,
            'videoWatches' => $videoWatches,
            'withdrawals' => $withdrawals,
        ]);
    }
    
    /**
     * Send a direct message to a specific Telegram user
     */
    public function sendMessage(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|min:1|max:4096',
            'parse_mode' => 'nullable|in:HTML,Markdown',
        ]);
        
        if (is_null($user->telegram_id)) {
            return redirect()->back()
                ->with('error', 'This user is not connected to Telegram.');
        }
        
        $message = $request->input('message');
        $parseMode = $request->input('parse_mode', 'HTML');
        
        $options = [
            'parse_mode' => $parseMode
        ];
        
        try {
            $response = $this->telegramService->sendMessage($user->telegram_id, $message, $options);
            
            if ($response) {
                return redirect()->back()
                    ->with('success', 'Message sent successfully to ' . $user->name);
            } else {
                return redirect()->back()
                    ->with('error', 'Failed to send message to ' . $user->name);
            }
        } catch (\Exception $e) {
            Log::error('Error sending Telegram message to user', [
                'user_id' => $user->id,
                'telegram_id' => $user->telegram_id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Error sending message: ' . $e->getMessage());
        }
    }
    
    /**
     * Bulk action (message, ban, etc.) on selected users
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:message,ban,unban',
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
            'bulk_message' => 'required_if:action,message|string|max:4096',
        ]);
        
        $userIds = $request->input('users');
        $action = $request->input('action');
        $results = [
            'success' => 0,
            'failed' => 0,
            'not_applicable' => 0
        ];
        
        $users = User::whereIn('id', $userIds)->whereNotNull('telegram_id')->get();
        
        foreach ($users as $user) {
            try {
                switch ($action) {
                    case 'message':
                        $message = $request->input('bulk_message');
                        $response = $this->telegramService->sendMessage($user->telegram_id, $message);
                        
                        if ($response) {
                            $results['success']++;
                        } else {
                            $results['failed']++;
                        }
                        break;
                        
                    case 'ban':
                        if (!$user->is_banned) {
                            $user->is_banned = true;
                            $user->save();
                            $results['success']++;
                        } else {
                            $results['not_applicable']++;
                        }
                        break;
                        
                    case 'unban':
                        if ($user->is_banned) {
                            $user->is_banned = false;
                            $user->save();
                            $results['success']++;
                        } else {
                            $results['not_applicable']++;
                        }
                        break;
                }
            } catch (\Exception $e) {
                Log::error('Error performing bulk action on user', [
                    'user_id' => $user->id,
                    'action' => $action,
                    'error' => $e->getMessage()
                ]);
                
                $results['failed']++;
            }
        }
        
        return redirect()->route('admin.telegram.users')
            ->with('success', "Bulk action completed: {$results['success']} succeeded, {$results['failed']} failed, {$results['not_applicable']} not applicable");
    }
    
    /**
     * Update a user's Telegram-specific information
     */
    public function updateTelegramInfo(Request $request, User $user)
    {
        $request->validate([
            'telegram_username' => 'nullable|string|max:255',
            'telegram_notes' => 'nullable|string|max:1000'
        ]);
        
        try {
            $user->telegram_username = $request->input('telegram_username');
            
            // Store notes in a JSON field or create a separate model if needed
            $user->notes = $request->input('telegram_notes');
            
            $user->save();
            
            return redirect()->back()
                ->with('success', 'Telegram information updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating Telegram information', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Error updating information: ' . $e->getMessage());
        }
    }
    
    /**
     * Get user registration trends
     */
    private function getNewUsersTrend($period, $limit)
    {
        switch ($period) {
            case 'daily':
                $groupFormat = 'Y-m-d';
                $labelFormat = 'M d';
                $subFunc = 'subDays';
                break;
                
            case 'weekly':
                $groupFormat = 'YW';
                $labelFormat = '\Week W';
                $subFunc = 'subWeeks';
                break;
                
            case 'monthly':
                $groupFormat = 'Y-m';
                $labelFormat = 'M Y';
                $subFunc = 'subMonths';
                break;
                
            default:
                return [];
        }
        
        $data = DB::table('users')
            ->whereNotNull('telegram_id')
            ->where('created_at', '>=', Carbon::now()->{$subFunc}($limit))
            ->selectRaw('COUNT(*) as count, DATE_FORMAT(created_at, "' . $groupFormat . '") as period')
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get()
            ->keyBy('period');
        
        $result = [];
        
        // Fill in missing periods with zeros
        for ($i = $limit; $i >= 0; $i--) {
            $date = Carbon::now()->{$subFunc}($i);
            $periodKey = $date->format($groupFormat);
            
            if ($period === 'weekly') {
                $label = 'Week ' . $date->format('W');
            } else {
                $label = $date->format($labelFormat);
            }
            
            $result[] = [
                'label' => $label,
                'count' => $data->get($periodKey)->count ?? 0,
                'period' => $periodKey
            ];
        }
        
        return $result;
    }

    /**
     * Toggle user ban status
     */
    public function toggleBan(User $user)
    {
        try {
            if (!$user->telegram_id) {
                return back()->with('error', 'User is not connected to Telegram');
            }

            $user->is_banned = !$user->is_banned;
            $user->save();

            if ($user->is_banned) {
                // Create notification
                $user->notifications()->create([
                    'type' => 'system',
                    'title' => 'Account Suspended',
                    'message' => 'Your account has been suspended by an administrator.',
                    'data' => ['action' => 'account_suspended']
                ]);

                // Send Telegram message
                try {
                    $this->telegramService->sendMessage(
                        $user->telegram_id,
                        "⚠️ *Account Suspended*\n\nYour account has been suspended by an administrator. Please contact support for more information."
                    );
                } catch (\Exception $e) {
                    \Log::error('Failed to send Telegram ban notification: ' . $e->getMessage());
                }

                return back()->with('success', 'User has been banned successfully');
            } else {
                // Create notification
                $user->notifications()->create([
                    'type' => 'system',
                    'title' => 'Account Restored',
                    'message' => 'Your account has been restored by an administrator.',
                    'data' => ['action' => 'account_restored']
                ]);

                // Send Telegram message
                try {
                    $this->telegramService->sendMessage(
                        $user->telegram_id,
                        "✅ *Account Restored*\n\nYour account has been restored by an administrator. You can now use the app again."
                    );
                } catch (\Exception $e) {
                    \Log::error('Failed to send Telegram unban notification: ' . $e->getMessage());
                }

                return back()->with('success', 'User has been unbanned successfully');
            }
        } catch (\Exception $e) {
            \Log::error('Error toggling user ban status: ' . $e->getMessage());
            return back()->with('error', 'Failed to update user ban status. Please try again.');
        }
    }
} 