<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;

class TelegramService
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
     * Validate Telegram Web App data using HMAC-SHA256
     *
     * @param array $data The received data from Telegram Web App
     * @return bool Whether the data is valid
     */
    public function validateTelegramData($data): bool
    {
        if (empty($data) || empty($data['hash'])) {
            return false;
        }

        $botToken = config('services.telegram.bot_token');
        
        if (empty($botToken)) {
            Log::error('Telegram bot token not configured');
            return false;
        }

        // Get the hash from the data
        $hash = $data['hash'];
        unset($data['hash']);

        // Sort the data alphabetically by key
        ksort($data);

        // Create the data check string
        $dataCheckString = '';
        foreach ($data as $key => $value) {
            $dataCheckString .= $key . '=' . $value . "\n";
        }
        
        // Remove the last newline character
        $dataCheckString = trim($dataCheckString);

        // Calculate the secret key using SHA-256
        $secretKey = hash_hmac('sha256', $botToken, 'WebAppData', true);
        
        // Calculate the hash
        $calculatedHash = hash_hmac('sha256', $dataCheckString, $secretKey);
        
        // Compare the hashes
        return hash_equals($calculatedHash, $hash);
    }

    /**
     * Parse and extract Telegram user data from the initData string
     *
     * @param string $initData The data string from Telegram Web App
     * @return array|null The parsed user data or null if invalid
     */
    public function parseInitData(string $initData): ?array
    {
        // Parse the query string
        parse_str($initData, $data);

        // Check if user data exists
        if (!isset($data['user'])) {
            return null;
        }

        // Decode user JSON
        $userData = json_decode($data['user'], true);
        if (!$userData) {
            return null;
        }

        // Validate Telegram data
        if (!$this->validateTelegramData($data)) {
            Log::warning('Invalid Telegram data signature', ['initData' => $initData]);
            return null;
        }

        return $userData;
    }
    
    /**
     * Send a text message to a Telegram user
     * 
     * @param string|int $chatId The chat ID to send the message to
     * @param string $message The message text
     * @param array $options Additional options for the sendMessage method
     * @return array|null The response from Telegram API or null on failure
     */
    public function sendMessage($chatId, string $message, array $options = []): ?array
    {
        $botToken = config('telegram.bot_token');
        
        if (empty($botToken)) {
            Log::error('Telegram bot token not configured');
            return null;
        }
        
        try {
            $payload = array_merge([
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML', // Default to HTML parsing
            ], $options);
            
            $response = $this->getHttpClient()->post("https://api.telegram.org/bot{$botToken}/sendMessage", $payload);
            
            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Failed to send Telegram message', [
                    'error' => $response->json(),
                    'chat_id' => $chatId
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Error sending Telegram message', [
                'error' => $e->getMessage(),
                'chat_id' => $chatId
            ]);
            return null;
        }
    }
    
    /**
     * Send a photo to a Telegram user
     * 
     * @param string|int $chatId The chat ID to send the photo to
     * @param string|UploadedFile $photo The photo URL or uploaded file
     * @param string $caption Optional caption for the photo
     * @param array $options Additional options for the sendPhoto method
     * @return array|null The response from Telegram API or null on failure
     */
    public function sendPhoto($chatId, $photo, string $caption = '', array $options = []): ?array
    {
        $botToken = config('telegram.bot_token');
        
        if (empty($botToken)) {
            Log::error('Telegram bot token not configured');
            return null;
        }
        
        try {
            $payload = array_merge([
                'chat_id' => $chatId,
                'caption' => $caption,
                'parse_mode' => 'HTML', // Default to HTML parsing
            ], $options);
            
            // Handle different photo input types
            if ($photo instanceof UploadedFile) {
                $client = $this->getHttpClient();
                $response = $client->attach(
                    'photo', 
                    file_get_contents($photo->getRealPath()), 
                    $photo->getClientOriginalName()
                )->post("https://api.telegram.org/bot{$botToken}/sendPhoto", $payload);
            } else {
                $payload['photo'] = $photo;
                $response = $this->getHttpClient()->post("https://api.telegram.org/bot{$botToken}/sendPhoto", $payload);
            }
            
            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Failed to send Telegram photo', [
                    'error' => $response->json(),
                    'chat_id' => $chatId
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Error sending Telegram photo', [
                'error' => $e->getMessage(),
                'chat_id' => $chatId
            ]);
            return null;
        }
    }
    
    /**
     * Send a message to all Telegram users
     * 
     * @param string $message The message text
     * @param array $options Additional options for the sendMessage method
     * @return array Stats about the broadcast
     */
    public function broadcastMessage(string $message, array $options = []): array
    {
        $users = \App\Models\User::whereNotNull('telegram_id')->get();
        
        $results = [
            'total' => $users->count(),
            'success' => 0,
            'failed' => 0,
            'failures' => []
        ];
        
        foreach ($users as $user) {
            $response = $this->sendMessage($user->telegram_id, $message, $options);
            
            if ($response) {
                $results['success']++;
            } else {
                $results['failed']++;
                $results['failures'][] = [
                    'user_id' => $user->id,
                    'telegram_id' => $user->telegram_id,
                    'name' => $user->name
                ];
            }
        }
        
        return $results;
    }
    
    /**
     * Send a photo to all Telegram users
     * 
     * @param string|UploadedFile $photo The photo URL or uploaded file
     * @param string $caption Optional caption for the photo
     * @param array $options Additional options for the sendPhoto method
     * @return array Stats about the broadcast
     */
    public function broadcastPhoto($photo, string $caption = '', array $options = []): array
    {
        $users = \App\Models\User::whereNotNull('telegram_id')->get();
        
        $results = [
            'total' => $users->count(),
            'success' => 0,
            'failed' => 0,
            'failures' => []
        ];
        
        foreach ($users as $user) {
            $response = $this->sendPhoto($user->telegram_id, $photo, $caption, $options);
            
            if ($response) {
                $results['success']++;
            } else {
                $results['failed']++;
                $results['failures'][] = [
                    'user_id' => $user->id,
                    'telegram_id' => $user->telegram_id,
                    'name' => $user->name
                ];
            }
        }
        
        return $results;
    }
} 