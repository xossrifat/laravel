<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\TelegramNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the user's notifications.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->paginate(10);
        $unreadCount = $user->notifications()->where('is_read', false)->count();
        
        // If this is an AJAX request, return just the notifications HTML
        if ($request->ajax() || $request->has('ajax')) {
            return view('notifications.partials.notification-list', compact('notifications'));
        }
        
        // Normal page load - send pending notifications to Telegram
        // Note: This is now redundant as we have an Observer that automatically sends on creation
        // But we'll keep it for any old notifications that weren't sent yet
        $this->sendPendingTelegramNotifications($user);
        
        return view('notifications.index', compact('notifications', 'unreadCount'));
    }
    
    /**
     * Mark a notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        // Check if the notification belongs to the authenticated user
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $notification->update(['is_read' => true]);
        
        return back()->with('success', 'Notification marked as read.');
    }
    
    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Auth::user()->notifications()->where('is_read', false)->update(['is_read' => true]);
        
        return back()->with('success', 'All notifications marked as read.');
    }
    
    /**
     * Get unread notifications count for a user.
     */
    public function getUnreadCount()
    {
        $count = Auth::user()->notifications()->where('is_read', false)->count();
        
        return response()->json(['unread_count' => $count]);
    }
    
    /**
     * Send all pending notifications to Telegram for a user
     * 
     * @param \App\Models\User $user
     */
    private function sendPendingTelegramNotifications($user)
    {
        // If user has a Telegram ID, send unsent notifications
        if ($user->telegram_id) {
            $pendingNotifications = $user->notifications()
                ->where('sent_to_telegram', false)
                ->orderBy('created_at', 'desc')
                ->take(5) // Limit to 5 at a time to avoid spamming
                ->get();
                
            foreach ($pendingNotifications as $notification) {
                $notification->sendToTelegram();
            }
        }
    }
}
