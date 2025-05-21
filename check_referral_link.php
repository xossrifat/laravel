<?php
// Load Laravel environment
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create a dummy user with a referral code
$dummyUser = new \App\Models\User();
$dummyUser->referral_code = 'TEST123456';

// Get the actual setting from the database
$botUsername = \App\Models\Setting::where('key', 'telegram_bot_username')->first()?->value ?? 'DEFAULT';
echo "Bot username in database: " . $botUsername . PHP_EOL;

// Generate a referral link using the same logic as the service
$generatedLink = "https://t.me/{$botUsername}/app?startapp=referral_{$dummyUser->referral_code}";
echo "Generated referral link: " . $generatedLink . PHP_EOL;

// Use the actual service to generate a link
$referralService = new \App\Services\ReferralService();
$serviceLink = $referralService->generateTelegramReferralLink($dummyUser);
echo "Service-generated link: " . $serviceLink . PHP_EOL; 