<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ReferralService;
use App\Models\User;
use App\Models\Setting;
use App\Models\ReferralReward;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class FixReferralPercentages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'referrals:fix-percentages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix percentage-based referral rewards system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting referral percentage rewards fix...');

        // Check notification enum
        $this->checkNotificationsEnum();

        // Check rewards tables
        $this->checkRewardsTable();

        // Check services registration
        $this->checkServicesRegistration();

        // Test creating a reward
        $this->testCreateReward();

        // List users with referrals
        $this->listUsersWithReferrals();

        // Final steps
        $this->info('Fix completed. The percentage-based referral system should now work correctly.');
        $this->info('To fully ensure changes take effect, run:');
        $this->line('  php artisan cache:clear');
        $this->line('  php artisan view:clear');
        $this->line('  php artisan config:clear');
        $this->line('  php artisan route:clear');
    }

    /**
     * Check and fix the notifications enum
     */
    private function checkNotificationsEnum()
    {
        $this->info('Checking notifications enum...');
        
        try {
            $result = DB::select("SHOW COLUMNS FROM notifications WHERE Field = 'type'");
            
            if (!empty($result)) {
                $typeInfo = $result[0];
                $this->info('Current type values: ' . $typeInfo->Type);
                
                if (strpos($typeInfo->Type, "'referral'") !== false) {
                    $this->info('✓ The referral type is already in the enum.');
                } else {
                    $this->warn('The referral type is not in the enum. Adding it now...');
                    DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('system', 'ban', 'promo', 'other', 'referral') NOT NULL DEFAULT 'system'");
                    $this->info('✓ Added referral type to notifications enum.');
                }
            } else {
                $this->error('Could not find type column in notifications table!');
            }
        } catch (\Exception $e) {
            $this->error('Error checking notifications schema: ' . $e->getMessage());
        }
    }

    /**
     * Check rewards table schema and data
     */
    private function checkRewardsTable()
    {
        $this->info('Checking referral_rewards table...');
        
        // Check if all required columns exist
        $requiredColumns = [
            'id', 'user_id', 'referral_id', 'coins_earned', 
            'is_percentage_reward', 'percentage_rate', 'source_activity'
        ];
        
        $missingColumns = [];
        foreach ($requiredColumns as $column) {
            if (!Schema::hasColumn('referral_rewards', $column)) {
                $missingColumns[] = $column;
            }
        }
        
        if (empty($missingColumns)) {
            $this->info('✓ All required columns exist in referral_rewards table.');
        } else {
            $this->error('Missing columns in referral_rewards table: ' . implode(', ', $missingColumns));
            $this->info('Please run: php artisan migrate');
        }
        
        // Check percentage setting
        $percentageSetting = Setting::where('key', 'referral_percentage')->first();
        if ($percentageSetting) {
            $this->info('Current referral percentage: ' . $percentageSetting->value . '%');
        } else {
            $this->warn('No referral percentage setting found. Creating default (5%)...');
            Setting::create([
                'key' => 'referral_percentage',
                'value' => 5
            ]);
            $this->info('✓ Created default referral percentage setting: 5%');
        }
    }
    
    /**
     * Check service registrations
     */
    private function checkServicesRegistration()
    {
        $this->info('Checking service registrations...');
        
        try {
            $referralService = app(ReferralService::class);
            $this->info('✓ ReferralService is registered and can be resolved.');
        } catch (\Exception $e) {
            $this->error('Error resolving ReferralService: ' . $e->getMessage());
        }
    }
    
    /**
     * Test creating a reward
     */
    private function testCreateReward()
    {
        $this->info('Testing reward creation...');
        
        try {
            // Find a user with a referrer
            $user = User::whereNotNull('referred_by')->first();
            
            if (!$user) {
                $this->warn('No referred users found for testing.');
                return;
            }
            
            $referrer = User::find($user->referred_by);
            
            if (!$referrer) {
                $this->warn('Invalid referrer for test user.');
                return;
            }
            
            $this->info("Test user: {$user->name} (ID: {$user->id})");
            $this->info("Referrer: {$referrer->name} (ID: {$referrer->id})");
            
            // Test percentage setting again
            $referralPercentage = (float) (Setting::where('key', 'referral_percentage')->first()?->value ?? 5);
            $this->info("Referral percentage: {$referralPercentage}%");
            
            // Create a test reward
            $reward = new ReferralReward();
            $reward->user_id = $referrer->id;
            $reward->referral_id = $user->id;
            $reward->coins_earned = 10;
            $reward->is_percentage_reward = true;
            $reward->percentage_rate = $referralPercentage;
            $reward->source_activity = 'test';
            $reward->save();
            
            $this->info('✓ Successfully created test reward with ID: ' . $reward->id);
            
            // Test notification
            try {
                $notification = $referrer->notifications()->create([
                    'title' => 'Test Referral Notification',
                    'message' => 'This is a test notification for referral rewards.',
                    'type' => 'referral'
                ]);
                
                $this->info('✓ Successfully created test notification with ID: ' . $notification->id);
                
                // Clean up test notification
                $notification->delete();
            } catch (\Exception $e) {
                $this->error('Error creating test notification: ' . $e->getMessage());
            }
            
            // Clean up test reward
            $reward->delete();
            $this->info('✓ Test cleanup completed.');
        } catch (\Exception $e) {
            $this->error('Error testing reward creation: ' . $e->getMessage());
        }
    }
    
    /**
     * List users with referrals
     */
    private function listUsersWithReferrals()
    {
        $this->info('Finding users with referrals...');
        
        $referredUsers = User::whereNotNull('referred_by')->count();
        $this->info("Total users with referrers: {$referredUsers}");
        
        $referrers = User::whereIn('id', function($query) {
            $query->select('referred_by')
                ->from('users')
                ->whereNotNull('referred_by');
        })->get();
        
        $this->info("Total referrers: {$referrers->count()}");
        
        if ($referrers->count() > 0) {
            $this->info("Top referrers:");
            $table = [];
            foreach ($referrers->take(5) as $referrer) {
                $referralsCount = User::where('referred_by', $referrer->id)->count();
                $table[] = [
                    'ID' => $referrer->id,
                    'Name' => $referrer->name,
                    'Email' => $referrer->email,
                    'Referrals' => $referralsCount
                ];
            }
            $this->table(['ID', 'Name', 'Email', 'Referrals'], $table);
        }
    }
} 