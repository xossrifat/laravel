<?php

// Simple script to add Telegram bot token to .env file
$envFile = __DIR__ . '/.env';

if (!file_exists($envFile)) {
    echo "Error: .env file not found\n";
    exit(1);
}

$content = file_get_contents($envFile);

// Check if TELEGRAM_BOT_TOKEN is already set
if (strpos($content, 'TELEGRAM_BOT_TOKEN=') !== false) {
    // Update existing token
    $content = preg_replace(
        '/TELEGRAM_BOT_TOKEN=.*/',
        'TELEGRAM_BOT_TOKEN=6095121686:AAE5eSkO-NAu3-yWYYM0D6yKF9y4C-Q33uY',
        $content
    );
} else {
    // Add new token
    $content .= "\n\n# Telegram Bot Configuration\nTELEGRAM_BOT_TOKEN=6095121686:AAE5eSkO-NAu3-yWYYM0D6yKF9y4C-Q33uY\n";
}

// Save the updated content
file_put_contents($envFile, $content);

echo "Telegram bot token has been added to .env file\n"; 