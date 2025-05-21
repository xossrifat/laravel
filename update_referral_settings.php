<?php

// Bootstrap Laravel application
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;
use Illuminate\Support\Facades\DB;

echo "Starting referral settings update...\r\n";

// Get current settings
$currentSettings = Setting::whereIn('key', ['referral_reward', 'referral_percentage', 'referral_reward_amount', 'referral_reward_percentage'])->pluck('value', 'key')->toArray();

echo "Current referral settings: \r\n";
foreach ($currentSettings as $key => $value) {
    echo "- {$key}: {$value}\r\n";
}

// Migrate from old keys to new keys
if (isset($currentSettings['referral_reward']) && !isset($currentSettings['referral_reward_amount'])) {
    echo "Migrating referral_reward to referral_reward_amount...\r\n";
    
    Setting::updateOrCreate(
        ['key' => 'referral_reward_amount'],
        ['value' => $currentSettings['referral_reward']]
    );
    echo "Created/updated referral_reward_amount setting\r\n";
}

if (isset($currentSettings['referral_percentage']) && !isset($currentSettings['referral_reward_percentage'])) {
    echo "Migrating referral_percentage to referral_reward_percentage...\r\n";
    
    Setting::updateOrCreate(
        ['key' => 'referral_reward_percentage'],
        ['value' => $currentSettings['referral_percentage']]
    );
    echo "Created/updated referral_reward_percentage setting\r\n";
}

// Create default settings if needed
if (!isset($currentSettings['referral_reward_amount']) && !isset($currentSettings['referral_reward'])) {
    echo "Creating default referral_reward_amount setting...\r\n";
    Setting::create(['key' => 'referral_reward_amount', 'value' => '300']);
    echo "Created default referral_reward_amount setting\r\n";
}

if (!isset($currentSettings['referral_reward_percentage']) && !isset($currentSettings['referral_percentage'])) {
    echo "Creating default referral_reward_percentage setting...\r\n";
    Setting::create(['key' => 'referral_reward_percentage', 'value' => '10']);
    echo "Created default referral_reward_percentage setting\r\n";
}

// Testing that the ReferralReward model works
echo "\r\nTesting ReferralReward model...\r\n";
try {
    $rewardsCount = \App\Models\ReferralReward::count();
    echo "Found {$rewardsCount} referral rewards\r\n";
    
    // Check reward types
    $fixedRewards = \App\Models\ReferralReward::where('is_percentage_reward', false)->count();
    $percentRewards = \App\Models\ReferralReward::where('is_percentage_reward', true)->count();
    
    echo "Fixed rewards: {$fixedRewards}\r\n";
    echo "Percentage rewards: {$percentRewards}\r\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\r\n";
}

// Get updated settings
$updatedSettings = Setting::whereIn('key', ['referral_reward_amount', 'referral_reward_percentage'])->pluck('value', 'key')->toArray();

echo "\r\nFinal updated settings: \r\n";
foreach ($updatedSettings as $key => $value) {
    echo "- {$key}: {$value}\r\n";
}

echo "Settings update completed.\r\n"; 