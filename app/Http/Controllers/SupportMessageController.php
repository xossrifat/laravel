<?php

namespace App\Http\Controllers;

use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportMessageController extends Controller
{
    /**
     * Show the form to send a new support message.
     */
    public function create()
    {
        return view('support.create');
    }

    /**
     * Store a new support message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $supportMessage = new SupportMessage([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'new',
        ]);

        $supportMessage->save();

        return redirect()->route('support.history')
            ->with('success', 'Your message has been sent. We will respond shortly.');
    }

    /**
     * Display the user's support message history.
     */
    public function history()
    {
        $messages = Auth::user()->supportMessages()
            ->latest()
            ->get();

        return view('support.history', compact('messages'));
    }

    /**
     * Display a specific support message.
     */
    public function show(SupportMessage $message)
    {
        // Check if the message belongs to the authenticated user
        if ($message->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('support.show', compact('message'));
    }
}
