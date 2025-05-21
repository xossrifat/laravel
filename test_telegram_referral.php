<?php

// Script to verify and test Telegram referral code extraction
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Log;

echo "======= Telegram Mini App Referral Code Test =======\n\n";

// Function to simulate extracting referral code from different formats
function testExtractReferralCode($input, $format) {
    echo "Testing $format: $input\n";
    $referralCode = null;
    
    if (strpos($input, 'startapp=referral_') !== false) {
        // Extract from URL query parameter
        if (preg_match('/startapp=referral_([A-Z0-9]+)/i', $input, $matches)) {
            $referralCode = $matches[1];
        }
    } else if (strpos($input, 'referral_') === 0) {
        // Extract from start_param direct format
        $referralCode = substr($input, 9);
    } else if (preg_match('/referral_([A-Z0-9]+)/i', $input, $matches)) {
        // Extract from path component
        $referralCode = $matches[1];
    }
    
    if ($referralCode) {
        echo "✅ Extracted referral code: $referralCode\n";
    } else {
        echo "❌ Failed to extract referral code\n";
    }
    
    echo "\n";
    return $referralCode;
}

// Test cases
$testCases = [
    // URL formats
    'https://t.me/SmsSB_bot/app?startapp=referral_BD5FB577' => 'Full URL format',
    'app?startapp=referral_BD5FB577' => 'Partial URL format',
    '?startapp=referral_BD5FB577' => 'Query parameter only',
    
    // Direct start_param format from Telegram WebApp
    'referral_BD5FB577' => 'Direct start_param format',
    
    // Path component format
    '/telegram/miniapp-ref/referral_BD5FB577' => 'URL path format',
    
    // Invalid formats - should fail
    'https://t.me/SmsSB_bot/app?start=ref_BD5FB577' => 'Invalid format (old format)',
    'https://t.me/SmsSB_bot/app?other=referral_BD5FB577' => 'Invalid param name',
    'https://t.me/SmsSB_bot/app?startapp=invalid_BD5FB577' => 'Invalid prefix'
];

// Run tests
foreach ($testCases as $input => $format) {
    testExtractReferralCode($input, $format);
}

// Simulate how our script parses params
echo "======= How JS Will Parse This =======\n\n";

// Function to simulate JS parsing from WebApp
function simulateJsParsing($url) {
    echo "Input URL: $url\n\n";
    
    // Method 1: Parse from URL
    $referralCode = null;
    if (strpos($url, 'startapp=referral_') !== false) {
        if (preg_match('/startapp=referral_([A-Z0-9]+)/i', $url, $matches)) {
            $referralCode = $matches[1];
            echo "Method 1 (URL query): Found code $referralCode\n";
        }
    }
    
    // Method 2: Parse from path
    if (!$referralCode && preg_match('/referral_([A-Z0-9]+)/i', $url, $matches)) {
        $referralCode = $matches[1];
        echo "Method 2 (URL path): Found code $referralCode\n";
    }
    
    echo "\nFinal result: " . ($referralCode ? "✅ Code: $referralCode" : "❌ No code found") . "\n\n";
}

// Test with the actual problem URL
simulateJsParsing('https://t.me/SmsSB_bot/app?startapp=referral_BD5FB577');

echo "======= Setup Verification =======\n\n";

// Check that routes exist
try {
    $hasRoute = \Illuminate\Support\Facades\Route::has('telegram.miniapp.referral');
    echo "Mini App Referral Route exists: " . ($hasRoute ? "✅ Yes" : "❌ No") . "\n";
} catch (\Exception $e) {
    echo "Error checking routes: " . $e->getMessage() . "\n";
}

// Check bot username from database
try {
    $botUsername = \App\Models\Setting::where('key', 'telegram_bot_username')->first()?->value;
    echo "Bot username in database: " . ($botUsername ? "✅ $botUsername" : "❌ Not set") . "\n";
} catch (\Exception $e) {
    echo "Error checking bot username: " . $e->getMessage() . "\n";
}

echo "\nTest complete. Please ensure your JavaScript is correctly parsing the startapp parameter.\n"; 