<?php

namespace App\Services;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramNotificationService
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
     * Send a notification to a user via Telegram if they have a Telegram ID
     *
     * @param User $user The user to notify
     * @param string $title The notification title
     * @param string $message The notification message
     * @param string $type The notification type
     * @return bool Whether the notification was sent successfully
     */
    public function sendNotification(User $user, string $title, string $message, string $type = 'system'): bool
    {
        // Check if user has a Telegram ID
        if (empty($user->telegram_id)) {
            Log::info("Cannot send Telegram notification - user has no Telegram ID", [
                'user_id' => $user->id,
                'notification' => $title
            ]);
            return false;
        }
        
        // Get bot token
        $botToken = config('telegram.bot_token');
        
        if (empty($botToken)) {
            Log::warning("Cannot send Telegram notification - bot token not configured");
            return false;
        }
        
        try {
            // Format the message nicely with emojis based on type
            $emoji = $this->getEmojiForType($type);
            $formattedMessage = "*{$emoji} {$title}*\n\n{$message}";
            
            // Add a timestamp
            $formattedMessage .= "\n\n_Sent: " . now()->format('Y-m-d H:i') . "_";
            
            // Send the message
            $response = $this->getHttpClient()->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $user->telegram_id,
                'text' => $formattedMessage,
                'parse_mode' => 'Markdown'
            ]);
            
            if ($response->successful()) {
                Log::info("Telegram notification sent successfully", [
                    'user_id' => $user->id,
                    'telegram_id' => $user->telegram_id,
                    'title' => $title
                ]);
                return true;
            } else {
                Log::warning("Failed to send Telegram notification", [
                    'user_id' => $user->id,
                    'telegram_id' => $user->telegram_id,
                    'response' => $response->json()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Exception sending Telegram notification", [
                'user_id' => $user->id,
                'telegram_id' => $user->telegram_id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Get an appropriate emoji for a notification type
     *
     * @param string $type
     * @return string
     */
    private function getEmojiForType(string $type): string
    {
        switch ($type) {
            case 'referral':
                return '🎁';
            case 'reward':
                return '💰';
            case 'transaction':
                return '💳';
            case 'system':
                return '⚙️';
            case 'warning':
                return '⚠️';
            case 'success':
                return '✅';
            case 'error':
                return '❌';
            default:
                return '📢';
        }
    }
    
    /**
     * Send a notification to multiple users via Telegram
     *
     * @param array $users Array of User objects
     * @param string $title The notification title
     * @param string $message The notification message
     * @param string $type The notification type
     * @return array Array of user IDs that received the notification
     */
    public function sendBulkNotifications(array $users, string $title, string $message, string $type = 'system'): array
    {
        $successfulIds = [];
        
        foreach ($users as $user) {
            if ($this->sendNotification($user, $title, $message, $type)) {
                $successfulIds[] = $user->id;
            }
        }
        
        return $successfulIds;
    }
} 