<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Setting;
use App\Models\ReferralReward;
use App\Services\ReferralService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

echo "Referral Percentage Test Script\n";
echo "==============================\n\n";

// Step 1: Check the settings
echo "Checking referral settings...\n";
$settings = Setting::whereIn('key', ['referral_reward_amount', 'referral_reward_percentage', 'referral_reward', 'referral_percentage'])
    ->pluck('value', 'key')
    ->toArray();

print_r($settings);

// Step 2: Find a user who has been referred by someone
echo "\nFinding users with referrers...\n";
$referredUsers = User::whereNotNull('referred_by')->get();

if ($referredUsers->isEmpty()) {
    echo "No users found with referrers. Creating a test relationship.\n";
    
    // Create a test referral relationship if none exists
    $referrer = User::where('is_admin', false)->first();
    $referred = User::where('id', '!=', $referrer->id)
        ->where('is_admin', false)
        ->whereNull('referred_by')
        ->first();
    
    if ($referred && $referrer) {
        $referred->referred_by = $referrer->id;
        $referred->save();
        
        echo "Created test relationship: User {$referred->id} referred by User {$referrer->id}\n";
        
        // Use this as our test user
        $testUser = $referred;
    } else {
        echo "Could not find suitable users to create a test relationship.\n";
        exit;
    }
} else {
    // Just use the first one we found
    $testUser = $referredUsers->first();
    echo "Found user {$testUser->id} with referrer {$testUser->referred_by}\n";
}

// Step 3: Test the percentage reward function directly
echo "\nTesting percentage reward function...\n";

$referralService = new ReferralService();
$result = $referralService->processPercentageReward($testUser, 100, 'test');

echo "Percentage reward result: " . ($result ? "SUCCESS" : "FAILED") . "\n";

// Step 4: Check if rewards were created
echo "\nChecking for rewards in database...\n";
$rewards = ReferralReward::where('referral_id', $testUser->id)
    ->where('is_percentage_reward', true)
    ->where('source_activity', 'test')
    ->orderBy('created_at', 'desc')
    ->get();

if ($rewards->isEmpty()) {
    echo "No percentage rewards found for this test.\n";
    
    // Check logs
    echo "\nChecking logs for clues...\n";
    $logs = DB::table('logs')
        ->where('message', 'like', '%percentage referral%')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get()
        ->toArray();
        
    if (!empty($logs)) {
        foreach ($logs as $log) {
            echo "Log entry: {$log->message}\n{$log->context}\n\n";
        }
    } else {
        echo "No relevant logs found. Try checking the Laravel log file directly.\n";
    }
} else {
    echo "Found " . count($rewards) . " percentage rewards:\n";
    foreach ($rewards as $reward) {
        echo "- Reward ID: {$reward->id}, Coins: {$reward->coins_earned}, Created: {$reward->created_at}\n";
    }
}

echo "\nTest completed.\n"; 