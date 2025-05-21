<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiveChatController extends Controller
{
    /**
     * Display a list of all users with chat sessions.
     */
    public function index()
    {
        // Get all users that have sent chat messages
        // Group by user and get the latest message for each
        $users = User::whereHas('chatMessages')
            ->withCount(['chatMessages as unread_messages' => function ($query) {
                $query->where('is_admin', false)->whereNull('read_at');
            }])
            ->withCount('chatMessages')
            ->orderByDesc('unread_messages')
            ->orderByDesc('chat_messages_count')
            ->paginate(20);
        
        return view('admin.chat.index', compact('users'));
    }
    
    /**
     * Display the chat session with a specific user.
     */
    public function show(User $user)
    {
        // Get chat messages with the user
        $messages = ChatMessage::where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Mark user messages as read
        ChatMessage::where('user_id', $user->id)
            ->where('is_admin', false)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        return view('admin.chat.show', compact('user', 'messages'));
    }
    
    /**
     * Send a message to a user.
     */
    public function sendMessage(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);
        
        $message = new ChatMessage([
            'user_id' => $user->id,
            'admin_id' => Auth::guard('admin')->id(),
            'message' => $request->message,
            'is_admin' => true,
        ]);
        
        $message->save();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'html' => view('admin.chat.partials.message', ['message' => $message])->render()
            ]);
        }
        
        return redirect()->route('admin.chat.show', $user)
            ->with('success', 'Message sent successfully.');
    }
    
    /**
     * Check for new messages from a user.
     */
    public function checkNew(Request $request, User $user)
    {
        $lastId = $request->last_id ?? 0;
        
        $messages = ChatMessage::where('user_id', $user->id)
            ->where('id', '>', $lastId)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Mark new user messages as read
        ChatMessage::where('user_id', $user->id)
            ->where('is_admin', false)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        if ($request->expectsJson()) {
            $html = '';
            foreach ($messages as $message) {
                $html .= view('admin.chat.partials.message', ['message' => $message])->render();
            }
            
            return response()->json([
                'success' => true,
                'messages' => $messages,
                'html' => $html,
                'last_id' => $messages->last() ? $messages->last()->id : $lastId
            ]);
        }
        
        return back();
    }
    
    /**
     * Get users with unread messages (for notifications).
     */
    public function getUnreadCount()
    {
        $count = User::whereHas('chatMessages', function ($query) {
            $query->where('is_admin', false)->whereNull('read_at');
        })->count();
        
        return response()->json([
            'count' => $count
        ]);
    }
} 