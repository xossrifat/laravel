<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiveChatController extends Controller
{
    /**
     * Display the live chat interface.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's chat messages
        $messages = ChatMessage::where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Mark all admin messages as read
        ChatMessage::where('user_id', $user->id)
            ->where('is_admin', true)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        return view('chat.index', compact('messages'));
    }
    
    /**
     * Store a new chat message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);
        
        $message = new ChatMessage([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_admin' => false,
        ]);
        
        $message->save();
        
        // If request wants JSON, return the message for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'html' => view('chat.partials.message', ['message' => $message])->render()
            ]);
        }
        
        return redirect()->route('chat.index')
            ->with('success', 'Message sent successfully.');
    }
    
    /**
     * Load more messages (for AJAX scrollback)
     */
    public function loadMore(Request $request)
    {
        $request->validate([
            'before_id' => 'required|integer|exists:chat_messages,id'
        ]);
        
        $user = Auth::user();
        $beforeId = $request->before_id;
        
        $messages = ChatMessage::where('user_id', $user->id)
            ->where('id', '<', $beforeId)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->sortBy('created_at');
        
        if ($request->expectsJson()) {
            $html = '';
            foreach ($messages as $message) {
                $html .= view('chat.partials.message', ['message' => $message])->render();
            }
            
            return response()->json([
                'success' => true,
                'messages' => $messages,
                'html' => $html,
                'has_more' => $messages->count() == 20
            ]);
        }
        
        return back();
    }
    
    /**
     * Check for new messages
     */
    public function checkNew(Request $request)
    {
        $user = Auth::user();
        $lastId = $request->last_id ?? 0;
        
        $messages = ChatMessage::where('user_id', $user->id)
            ->where('id', '>', $lastId)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Mark new admin messages as read
        ChatMessage::where('user_id', $user->id)
            ->where('is_admin', true)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        if ($request->expectsJson()) {
            $html = '';
            foreach ($messages as $message) {
                $html .= view('chat.partials.message', ['message' => $message])->render();
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
} 