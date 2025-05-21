<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\ReferralReward;
use Illuminate\Support\Facades\Log;

echo "Manual Coin Add Test\n";
echo "===================\n\n";

// Find a user who has been referred
$testUser = User::whereNotNull('referred_by')->first();

if (!$testUser) {
    echo "Error: Could not find a user with a referrer.\n";
    exit;
}

echo "Found test user ID {$testUser->id} who was referred by user ID {$testUser->referred_by}\n";
echo "Current coins: {$testUser->coins}\n";

// Add coins to the user's account
$coinsToAdd = 100;
$testUser->increment('coins', $coinsToAdd);

echo "Added {$coinsToAdd} coins to user {$testUser->id}\n";
echo "New balance: {$testUser->coins}\n";

// Wait a moment for any observers to process
echo "Waiting for observer processing...\n";
sleep(2);

// Check if a reward was created
$latestReward = ReferralReward::where('referral_id', $testUser->id)
    ->where('is_percentage_reward', true)
    ->where('source_activity', 'automatic_observer')
    ->orderBy('created_at', 'desc')
    ->first();

if ($latestReward) {
    echo "\nSUCCESS! Referral reward was created:\n";
    echo "- Reward ID: {$latestReward->id}\n";
    echo "- Coins given to referrer: {$latestReward->coins_earned}\n";
    echo "- Created at: {$latestReward->created_at}\n";
} else {
    echo "\nFAILED: No referral reward was created via the observer.\n";
    echo "Check the logs for more information.\n";
}

echo "\nTest completed.\n"; 