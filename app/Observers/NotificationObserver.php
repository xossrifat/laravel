<?php

namespace App\Observers;

use App\Models\Notification;
use App\Services\TelegramNotificationService;
use Illuminate\Support\Facades\Log;

class NotificationObserver
{
    /**
     * Handle the Notification "created" event.
     */
    public function created(Notification $notification): void
    {
        // After a notification is created, immediately send it to Telegram if the user has a Telegram ID
        if ($notification->user && $notification->user->telegram_id) {
            try {
                // Send the notification to Telegram
                $notification->sendToTelegram();
                
                // Log the action
                Log::info('Notification automatically sent to Telegram', [
                    'notification_id' => $notification->id,
                    'user_id' => $notification->user_id,
                    'telegram_id' => $notification->user->telegram_id
                ]);
            } catch (\Exception $e) {
                // Log any errors but don't stop execution
                Log::error('Failed to automatically send notification to Telegram', [
                    'notification_id' => $notification->id,
                    'user_id' => $notification->user_id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
} 