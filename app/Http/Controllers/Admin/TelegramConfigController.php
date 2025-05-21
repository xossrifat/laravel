<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TelegramConfigController extends Controller
{
    /**
     * Get HTTP client with proxy configuration if needed
     * 
     * @return \Illuminate\Http\Client\PendingRequest
     */
    protected function getHttpClient()
    {
        $client = Http::timeout(15);
        
        // Check if we're in production and proxy is needed
        if (app()->environment('production') && config('services.telegram.use_proxy', false)) {
            $proxyUrl = config('services.telegram.proxy_url');
            if ($proxyUrl) {
                $client->withOptions([
                    'proxy' => $proxyUrl,
                    'verify' => false, // May need to disable SSL verification when using some proxies
                ]);
                Log::info('Using proxy for Telegram API request');
            }
        }
        
        return $client;
    }

    /**
     * Display the Telegram configuration page.
     */
    public function index()
    {
        $botToken = config('telegram.bot_token', '');
        $autoLogin = Setting::where('key', 'telegram_auto_login')->first()?->value ?? 'false';
        
        $botInfo = null;
        $botError = null;
        
        // Try to get bot info if token is set
        if ($botToken) {
            try {
                $response = $this->getHttpClient()->get("https://api.telegram.org/bot{$botToken}/getMe");
                if ($response->successful()) {
                    $botInfo = $response->json()['result'] ?? null;
                } else {
                    $botError = $response->json()['description'] ?? 'Unknown error';
                }
            } catch (\Exception $e) {
                Log::error('Error fetching Telegram bot info', [
                    'error' => $e->getMessage()
                ]);
                $botError = $e->getMessage();
            }
        }
        
        return view('admin.telegram.config', [
            'botToken' => $botToken,
            'botInfo' => $botInfo,
            'botError' => $botError,
            'autoLogin' => $autoLogin === 'true',
            'webhookUrl' => url('/api/telegram/webhook'),
        ]);
    }
    
    /**
     * Update the Telegram configuration.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'bot_token' => 'nullable|string',
            'auto_login' => 'required|boolean',
            'telegram_bot_username' => 'required|string|max:255',
            'use_proxy' => 'boolean|nullable',
            'proxy_url' => 'nullable|string',
        ]);
        
        // Update bot token in .env
        if (isset($validated['bot_token']) && !empty($validated['bot_token'])) {
            $this->updateEnvVariable('TELEGRAM_BOT_TOKEN', $validated['bot_token']);
        }
        
        // Update proxy settings if provided
        if (isset($validated['use_proxy'])) {
            $this->updateEnvVariable('TELEGRAM_USE_PROXY', $validated['use_proxy'] ? 'true' : 'false');
            
            if (isset($validated['proxy_url']) && !empty($validated['proxy_url'])) {
                $this->updateEnvVariable('TELEGRAM_PROXY_URL', $validated['proxy_url']);
            }
        }
        
        // Update settings
        Setting::updateOrCreate(
            ['key' => 'telegram_auto_login'],
            ['value' => $validated['auto_login'] ? 'true' : 'false']
        );
        
        // Update bot username for referral links
        Setting::updateOrCreate(
            ['key' => 'telegram_bot_username'],
            ['value' => $validated['telegram_bot_username']]
        );
        
        return redirect()->route('admin.telegram.config')
            ->with('success', 'Telegram configuration updated successfully.');
    }
    
    /**
     * Test the Telegram connection.
     */
    public function testConnection(Request $request)
    {
        $botToken = $request->input('bot_token', config('telegram.bot_token'));
        
        if (empty($botToken)) {
            return response()->json([
                'success' => false,
                'message' => 'Bot token is not configured'
            ]);
        }
        
        try {
            $response = $this->getHttpClient()->get("https://api.telegram.org/bot{$botToken}/getMe");
            
            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Connection successful',
                    'data' => $response->json()['result']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $response->json()['description'] ?? 'Unknown error'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error connecting to Telegram API: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Update an environment variable in the .env file.
     */
    private function updateEnvVariable($key, $value)
    {
        $path = base_path('.env');
        
        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            // Check if the key exists
            if (strpos($content, "{$key}=") !== false) {
                // Update existing variable
                $content = preg_replace(
                    "/^{$key}=.*/m", 
                    "{$key}={$value}", 
                    $content
                );
            } else {
                // Add new variable
                $content .= "\n{$key}={$value}\n";
            }
            
            file_put_contents($path, $content);
            
            // Clear config cache
            if (function_exists('artisan')) {
                \Artisan::call('config:clear');
            }
        }
    }
} 