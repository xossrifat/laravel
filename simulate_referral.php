<?php
// Load the Laravel framework
require_once __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Services\ReferralService;
use Illuminate\Support\Facades\Log;

// Force output to be displayed immediately
ini_set('implicit_flush', 1);
ob_implicit_flush(1);

echo "===== Referral Percentage Reward Simulation =====\n\n";

// Get referral percentage from database
$referralPercentage = \App\Models\Setting::where('key', 'referral_percentage')->first()?->value ?? '5';
echo "Current referral percentage: $referralPercentage%\n\n";

// Check notifications table
try {
    $notificationTypes = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM notifications WHERE Field = 'type'");
    if (!empty($notificationTypes)) {
        $typeInfo = $notificationTypes[0];
        echo "Notification type column info: " . $typeInfo->Type . "\n";
        if (strpos($typeInfo->Type, "'referral'") !== false) {
            echo "✅ 'referral' is a valid notification type.\n\n";
        } else {
            echo "❌ 'referral' is NOT a valid notification type.\n";
            echo "Valid types are: " . $typeInfo->Type . "\n\n";
        }
    }
} catch (\Exception $e) {
    echo "Error checking notifications: " . $e->getMessage() . "\n\n";
}

// Try to find users for simulation
$users = User::whereNotNull('referred_by')->take(5)->get();

if ($users->isEmpty()) {
    echo "No referred users found in the system for simulation.\n";
    exit;
}

echo "Found " . $users->count() . " referred users for simulation.\n\n";

// Select the first user to simulate earning coins
$testUser = $users->first();
$referrer = User::find($testUser->referred_by);

if (!$referrer) {
    echo "Could not find a valid referrer for user {$testUser->id}.\n";
    exit;
}

echo "Simulating user {$testUser->name} (ID: {$testUser->id}) earning coins.\n";
echo "Their referrer is {$referrer->name} (ID: {$referrer->id}).\n\n";

// Record coins before
$referrerCoinsBefore = $referrer->coins;
echo "Referrer coins BEFORE: $referrerCoinsBefore\n";

// Simulate earning coins
$coinsEarned = 100;
echo "User earned: $coinsEarned coins\n";
$expectedReward = ceil($coinsEarned * ($referralPercentage / 100));
echo "Expected referral reward: $expectedReward coins\n\n";

// Use referral service to process percentage reward
echo "Processing percentage referral reward...\n";
$referralService = new ReferralService();
$result = $referralService->processPercentageReward($testUser, $coinsEarned, 'simulation');

// Check result and update
$referrer = User::find($referrer->id); // Reload from DB to get updated values
$referrerCoinsAfter = $referrer->coins; 
echo "Referrer coins AFTER: $referrerCoinsAfter\n";
$actualReward = $referrerCoinsAfter - $referrerCoinsBefore;
echo "Actual coin increase: $actualReward\n\n";

// Check notifications
$newNotificationCount = $referrer->notifications()
    ->where('type', 'referral')
    ->where('created_at', '>=', now()->subMinutes(1))
    ->count();

echo "New referral notifications created: $newNotificationCount\n\n";

// Overall result
echo "Simulation result: " . ($result ? "SUCCESS" : "FAILED") . "\n";
echo "Coin reward was " . ($actualReward > 0 ? "applied" : "NOT applied") . ".\n";
echo "Notification was " . ($newNotificationCount > 0 ? "created" : "NOT created") . ".\n\n";

echo "Check logs for more details about what happened behind the scenes.\n"; 