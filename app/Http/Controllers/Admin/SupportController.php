<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SupportController extends Controller
{
    /**
     * Display a listing of support messages.
     */
    public function index()
    {
        // Assuming we have a support_messages table
        // If not, this would need to be created
        try {
            $messages = DB::table('support_messages')
                ->join('users', 'support_messages.user_id', '=', 'users.id')
                ->select('support_messages.*', 'users.name', 'users.email')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } catch (\Exception $e) {
            // If the table doesn't exist, return an empty collection
            Log::error('Error fetching support messages: ' . $e->getMessage());
            $messages = collect([]);
        }

        return view('admin.support.index', compact('messages'));
    }

    /**
     * Display all messages from a specific user.
     */
    public function messages(Request $request)
    {
        $userId = $request->input('user_id');
        
        try {
            if ($userId) {
                $user = User::findOrFail($userId);
                $messages = DB::table('support_messages')
                    ->where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->get();
                
                return view('admin.support.messages', compact('messages', 'user'));
            } else {
                return redirect()->route('admin.support.index')
                    ->withErrors(['error' => 'User ID is required']);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching user messages: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to load messages: ' . $e->getMessage()]);
        }
    }

    /**
     * Reply to a support message.
     */
    public function reply(Request $request, $messageId)
    {
        $request->validate([
            'reply' => 'required|string',
        ]);

        try {
            // Update the message with the reply
            DB::table('support_messages')
                ->where('id', $messageId)
                ->update([
                    'admin_reply' => $request->reply,
                    'status' => 'answered',
                    'replied_at' => now()
                ]);
            
            // You might want to send an email notification here
            
            return back()->with('success', 'Reply sent successfully.');
        } catch (\Exception $e) {
            Log::error('Error replying to message: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to send reply: ' . $e->getMessage()]);
        }
    }
}
