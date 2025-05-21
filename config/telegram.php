<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Telegram Bot API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the Telegram Bot API integration.
    |
    */

    'bot_token' => env('TELEGRAM_BOT_TOKEN', '6095121686:AAE5eSkO-NAu3-yWYYM0D6yKF9y4C-Q33uY'),
    
    'bot_username' => env('TELEGRAM_BOT_USERNAME', ''),
    
    // Used to validate the origin of requests
    'validate_webhook' => env('TELEGRAM_VALIDATE_WEBHOOK', true),
    
    // App info for Telegram Mini App
    'app_name' => env('TELEGRAM_APP_NAME', ' RewardBazar'),
    
    // Debug mode
    'debug' => env('TELEGRAM_DEBUG', false),
    
    // Default login behavior
    'auto_login' => env('TELEGRAM_AUTO_LOGIN', true),
]; 