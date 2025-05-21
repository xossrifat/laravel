<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\TelegramNotificationService;

class Notification extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'message',
        'type',
        'user_id',
        'is_read',
        'sent_to_telegram'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_read' => 'boolean',
        'sent_to_telegram' => 'boolean',
    ];
    
    /**
     * Get the user that the notification belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Send this notification to Telegram if user has Telegram ID
     * 
     * @return bool Whether the notification was sent
     */
    public function sendToTelegram(): bool
    {
        // Skip if already sent or user doesn't exist
        if ($this->sent_to_telegram || !$this->user) {
            return false;
        }
        
        $telegramService = app(TelegramNotificationService::class);
        
        $result = $telegramService->sendNotification(
            $this->user,
            $this->title,
            $this->message,
            $this->type
        );
        
        if ($result) {
            $this->sent_to_telegram = true;
            $this->save();
        }
        
        return $result;
    }
    
    /**
     * Get icon for a notification type
     * 
     * @return string HTML for the icon
     */
    public function getIconHtml(): string
    {
        switch ($this->type) {
            case 'referral':
                return '<span class="text-green-500"><i class="fas fa-gift"></i></span>';
            case 'reward':
                return '<span class="text-yellow-500"><i class="fas fa-coins"></i></span>';
            case 'transaction':
                return '<span class="text-blue-500"><i class="fas fa-credit-card"></i></span>';
            case 'system':
                return '<span class="text-gray-500"><i class="fas fa-cog"></i></span>';
            case 'warning':
                return '<span class="text-orange-500"><i class="fas fa-exclamation-triangle"></i></span>';
            case 'error':
                return '<span class="text-red-500"><i class="fas fa-times-circle"></i></span>';
            default:
                return '<span class="text-indigo-500"><i class="fas fa-bell"></i></span>';
        }
    }
    
    /**
     * Get the background color class for the notification
     * 
     * @return string Tailwind CSS class
     */
    public function getBackgroundClass(): string
    {
        if (!$this->is_read) {
            switch ($this->type) {
                case 'referral':
                    return 'bg-green-50 border-green-500';
                case 'reward':
                    return 'bg-yellow-50 border-yellow-500';
                case 'transaction':
                    return 'bg-blue-50 border-blue-500';
                case 'warning':
                    return 'bg-orange-50 border-orange-500';
                case 'error':
                    return 'bg-red-50 border-red-500';
                default:
                    return 'bg-indigo-50 border-indigo-500';
            }
        }
        
        return 'bg-gray-50 border-gray-300';
    }
    
    /**
     * Get the text color for the notification
     * 
     * @return string Tailwind CSS class
     */
    public function getTextClass(): string
    {
        if (!$this->is_read) {
            switch ($this->type) {
                case 'referral':
                    return 'text-green-700';
                case 'reward':
                    return 'text-yellow-700';
                case 'transaction':
                    return 'text-blue-700';
                case 'warning':
                    return 'text-orange-700';
                case 'error':
                    return 'text-red-700';
                default:
                    return 'text-indigo-700';
            }
        }
        
        return 'text-gray-700';
    }
}
