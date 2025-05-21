<?php

// Check required classes are available
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Setting;
use Illuminate\Support\Facades\App;

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check Telegram bot username
echo "== Telegram Bot Username Verification ==\n\n";

$botUsername = Setting::where('key', 'telegram_bot_username')->first()?->value;
echo "Current bot username: " . ($botUsername ?? 'Not set') . "\n";

// Update if needed
if (!$botUsername || $botUsername !== 'SmsSB_bot') {
    echo "Setting bot username to: SmsSB_bot\n";
    Setting::updateOrCreate(
        ['key' => 'telegram_bot_username'],
        ['value' => 'SmsSB_bot']
    );
} else {
    echo "Bot username is correctly set.\n";
}

// Update the referral links version
$timestamp = time();
Setting::updateOrCreate(
    ['key' => 'referral_links_version'],
    ['value' => $timestamp]
);
echo "Updated referral links version to: $timestamp\n\n";

// Verify the format of a referral link
echo "== Referral Link Format Verification ==\n\n";

$referralService = app(\App\Services\ReferralService::class);
$testUser = \App\Models\User::first();

if ($testUser) {
    $telegramLink = $referralService->generateTelegramReferralLink($testUser);
    echo "Generated test link: $telegramLink\n";
    
    // Check if link format is correct
    $expectedFormat = "https://t.me/SmsSB_bot/app?startapp=referral_";
    if (strpos($telegramLink, $expectedFormat) === 0) {
        echo "Link format appears correct.\n";
    } else {
        echo "Warning: Link format does not match expected pattern!\n";
        echo "Expected pattern: $expectedFormat{referral_code}\n";
    }
    
    echo "\nReferral code from link: " . substr($telegramLink, strlen($expectedFormat)) . "\n";
} else {
    echo "No users found for testing\n";
}

echo "\nVerification complete.\n"; 