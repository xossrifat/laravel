<?php
// Load the Laravel framework
require_once __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\ReferralReward;
use App\Models\Setting;
use App\Services\ReferralService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

echo "========== Percentage Referral Reward Test ==========\n\n";

// 1. Check if there are referred users in the system
echo "Checking for referred users...\n";
$referredUsers = User::whereNotNull('referred_by')->get();
echo "Found " . $referredUsers->count() . " referred users in the system.\n\n";

// 2. Check the referral percentage setting
echo "Checking referral percentage setting...\n";
$referralPercentage = Setting::where('key', 'referral_percentage')->first()?->value ?? 'not set';
echo "Current referral percentage: " . $referralPercentage . "%\n\n";

// 3. Check if we have any percentage-based rewards recorded
echo "Checking for existing percentage rewards...\n";
$percentageRewards = ReferralReward::where('is_percentage_reward', true)->get();
echo "Found " . $percentageRewards->count() . " percentage-based rewards in the system.\n\n";

// 4. Test the database schema for referral_rewards table
echo "Checking referral_rewards table schema...\n";
$columns = DB::select("SHOW COLUMNS FROM referral_rewards");
$expectedColumns = ['id', 'user_id', 'referral_id', 'coins_earned', 'is_percentage_reward', 'percentage_rate', 'source_activity', 'created_at', 'updated_at'];
$foundColumns = [];

foreach ($columns as $column) {
    $foundColumns[] = $column->Field;
}

$missingColumns = array_diff($expectedColumns, $foundColumns);
if (empty($missingColumns)) {
    echo "✅ All required columns exist in the referral_rewards table.\n\n";
} else {
    echo "❌ Missing columns in referral_rewards table: " . implode(', ', $missingColumns) . "\n\n";
}

// 5. Test creating a percentage reward directly
echo "Testing direct database insertion of percentage reward...\n";
try {
    if ($referredUsers->count() > 0) {
        // Take the first referred user for testing
        $testUser = $referredUsers->first();
        $referrer = User::find($testUser->referred_by);
        
        if ($referrer) {
            echo "Using test user: " . $testUser->name . " (ID: " . $testUser->id . ")\n";
            echo "Referrer: " . $referrer->name . " (ID: " . $referrer->id . ")\n";
            
            // Create a test reward
            $testReward = new ReferralReward();
            $testReward->user_id = $referrer->id; // The referrer gets the reward
            $testReward->referral_id = $testUser->id; // The user who triggered the reward
            $testReward->coins_earned = 10; // 10 test coins
            $testReward->is_percentage_reward = true;
            $testReward->percentage_rate = $referralPercentage ?: 5;
            $testReward->source_activity = 'test_script';
            $testReward->save();
            
            echo "✅ Successfully created test percentage reward record with ID: " . $testReward->id . "\n\n";
        } else {
            echo "❌ Could not find referrer for test user.\n\n";
        }
    } else {
        echo "❌ No referred users available for testing.\n\n";
    }
} catch (\Exception $e) {
    echo "❌ Error creating test reward: " . $e->getMessage() . "\n\n";
}

// 6. Test notifications table schema
echo "Checking notifications table schema...\n";
try {
    $notificationTypes = DB::select("SHOW COLUMNS FROM notifications WHERE Field = 'type'");
    if (!empty($notificationTypes)) {
        $typeInfo = $notificationTypes[0];
        echo "Notification type column info: " . $typeInfo->Type . "\n";
        if (strpos($typeInfo->Type, "'referral'") !== false) {
            echo "✅ 'referral' is a valid notification type.\n\n";
        } else {
            echo "❌ 'referral' is NOT a valid notification type. Valid types are: " . $typeInfo->Type . "\n\n";
        }
    } else {
        echo "❌ Could not find 'type' column in notifications table.\n\n";
    }
} catch (\Exception $e) {
    echo "❌ Error checking notifications schema: " . $e->getMessage() . "\n\n";
}

// 7. Test creating a notification
echo "Testing notification creation...\n";
try {
    if (isset($referrer)) {
        $notification = $referrer->notifications()->create([
            'title' => 'Test Notification',
            'message' => 'This is a test referral notification',
            'type' => 'referral'
        ]);
        
        echo "✅ Successfully created test notification with ID: " . $notification->id . "\n\n";
        
        // Clean up test notification
        $notification->delete();
        echo "Test notification deleted.\n\n";
    } else {
        echo "❌ No referrer user available for testing notifications.\n\n";
    }
} catch (\Exception $e) {
    echo "❌ Error creating test notification: " . $e->getMessage() . "\n\n";
}

// 8. Test the full percentage reward method
echo "Testing full percentage reward process...\n";
if (isset($testUser) && isset($referrer)) {
    try {
        $referralService = new ReferralService();
        $result = $referralService->processPercentageReward($testUser, 100, 'test_script');
        
        if ($result) {
            echo "✅ Referral percentage reward process completed successfully.\n\n";
        } else {
            echo "❌ Referral percentage reward process failed.\n\n";
        }
    } catch (\Exception $e) {
        echo "❌ Error in referral process: " . $e->getMessage() . "\n\n";
    }
} else {
    echo "❌ Cannot test full reward process without test users.\n\n";
}

echo "Test script completed.\n";
echo "Please check your application logs for more details about any errors.\n"; 