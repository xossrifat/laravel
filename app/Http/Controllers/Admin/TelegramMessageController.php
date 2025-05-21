<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TelegramMessageController extends Controller
{
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    /**
     * Display the Telegram messaging interface
     */
    public function index()
    {
        // Get stats about Telegram users
        $telegramUsers = User::whereNotNull('telegram_id')->count();
        $totalUsers = User::count();
        $telegramPercentage = $totalUsers > 0 ? round(($telegramUsers / $totalUsers) * 100) : 0;
        
        // Get recent messages if we want to implement message history in the future
        // $messages = TelegramMessage::latest()->take(10)->get();
        
        return view('admin.telegram.messages', [
            'telegramUsers' => $telegramUsers,
            'totalUsers' => $totalUsers,
            'telegramPercentage' => $telegramPercentage,
            // 'messages' => $messages,
        ]);
    }

    /**
     * Send a text message to all Telegram users
     */
    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|min:1|max:4096',
            'parse_mode' => 'nullable|in:HTML,Markdown',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $message = $request->input('message');
        $parseMode = $request->input('parse_mode', 'HTML');
        
        $options = [
            'parse_mode' => $parseMode
        ];

        try {
            $results = $this->telegramService->broadcastMessage($message, $options);
            
            Log::info('Telegram broadcast sent', $results);
            
            return redirect()->route('admin.telegram.messages')
                ->with('success', "Message successfully sent to {$results['success']} of {$results['total']} users.");
        } catch (\Exception $e) {
            Log::error('Error broadcasting Telegram message', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to send message: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Send a photo with optional caption to all Telegram users
     */
    public function sendPhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|file|image|max:10240', // 10MB max
            'caption' => 'nullable|string|max:1024',
            'parse_mode' => 'nullable|in:HTML,Markdown',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $photo = $request->file('photo');
        $caption = $request->input('caption', '');
        $parseMode = $request->input('parse_mode', 'HTML');
        
        $options = [
            'parse_mode' => $parseMode
        ];

        try {
            $results = $this->telegramService->broadcastPhoto($photo, $caption, $options);
            
            Log::info('Telegram photo broadcast sent', $results);
            
            return redirect()->route('admin.telegram.messages')
                ->with('success', "Photo successfully sent to {$results['success']} of {$results['total']} users.");
        } catch (\Exception $e) {
            Log::error('Error broadcasting Telegram photo', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to send photo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Send a test message to a specific user
     */
    public function sendTestMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_telegram_id' => 'required|string',
            'test_message' => 'required|string|min:1|max:4096',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $telegramId = $request->input('test_telegram_id');
        $message = $request->input('test_message');

        try {
            $response = $this->telegramService->sendMessage($telegramId, $message);
            
            if ($response) {
                return redirect()->back()
                    ->with('success', 'Test message sent successfully.');
            } else {
                return redirect()->back()
                    ->with('error', 'Failed to send test message.')
                    ->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error sending test message: ' . $e->getMessage())
                ->withInput();
        }
    }
} 