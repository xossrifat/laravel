<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixReferrals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'referrals:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix broken referrals and update Telegram bot username';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting referral system repair...');

        // Step 1: Check and fix Telegram bot username
        $this->checkAndFixTelegramBotUsername();
        
        // Step 2: Update referral links version
        $this->updateReferralLinksVersion();

        // Step 3: Check and fix broken referrals
        $this->checkAndFixBrokenReferrals();

        // Step 4: Verify referred_by relationships
        $this->verifyReferralRelationships();

        $this->info('Referral system repair completed.');
    }

    /**
     * Check and fix Telegram bot username
     */
    private function checkAndFixTelegramBotUsername()
    {
        $this->info('Checking Telegram bot username...');
        
        $botUsername = Setting::where('key', 'telegram_bot_username')->first()?->value;
        $this->info("Current bot username: " . ($botUsername ?? 'Not set'));
        
        if (!$botUsername || $botUsername !== 'SmsSB_bot') {
            $this->warn('Bot username needs to be updated.');
            Setting::updateOrCreate(
                ['key' => 'telegram_bot_username'],
                ['value' => 'SmsSB_bot']
            );
            $this->info('Bot username set to: SmsSB_bot');
        } else {
            $this->info('Bot username is correct.');
        }
    }

    /**
     * Update referral links version to force refresh cached links
     */
    private function updateReferralLinksVersion()
    {
        $this->info('Updating referral links version...');
        
        $currentTime = time();
        Setting::updateOrCreate(
            ['key' => 'referral_links_version'],
            ['value' => $currentTime]
        );
        
        $this->info("Referral links version updated to: $currentTime");
    }

    /**
     * Check for users with broken referrals and fix them
     */
    private function checkAndFixBrokenReferrals()
    {
        $this->info('Checking for broken referrals...');
        
        // Find users with referred_by values that don't match any user ids
        $brokenReferrals = User::whereNotNull('referred_by')
            ->whereRaw('referred_by NOT IN (SELECT id FROM users)')
            ->get();
            
        if ($brokenReferrals->count() > 0) {
            $this->warn("Found {$brokenReferrals->count()} users with invalid referrers.");
            
            foreach ($brokenReferrals as $user) {
                $this->line("- Fixing user ID {$user->id} ({$user->name}) with invalid referrer ID {$user->referred_by}");
                
                // Clear invalid referrer
                $user->referred_by = null;
                $user->save();
                
                // Log the change
                Log::info("Cleared invalid referrer", [
                    'user_id' => $user->id,
                    'previous_referrer_id' => $user->referred_by
                ]);
            }
            
            $this->info('Fixed broken referrals.');
        } else {
            $this->info('No broken referrals found.');
        }
    }

    /**
     * Verify all referral relationships are valid
     */
    private function verifyReferralRelationships()
    {
        $this->info('Verifying referral relationships...');
        
        // Count users who have referred others
        $referrersCount = User::whereIn('id', function ($query) {
            $query->select('referred_by')
                ->from('users')
                ->whereNotNull('referred_by')
                ->distinct();
        })->count();
        
        // Count users who have been referred
        $referredUsersCount = User::whereNotNull('referred_by')->count();
        
        $this->info("System has $referrersCount users who have referred others.");
        $this->info("System has $referredUsersCount users who were referred by someone.");
        
        // Check for discrepancies in referral_count
        $usersWithIncorrectCounts = DB::select("
            SELECT u.id, u.name, u.email, u.referral_count, COUNT(r.id) as actual_count 
            FROM users u
            LEFT JOIN users r ON r.referred_by = u.id
            GROUP BY u.id, u.name, u.email, u.referral_count
            HAVING u.referral_count <> COUNT(r.id)
        ");
        
        if (count($usersWithIncorrectCounts) > 0) {
            $this->warn("Found " . count($usersWithIncorrectCounts) . " users with incorrect referral counts.");
            
            foreach ($usersWithIncorrectCounts as $user) {
                $this->line("- Fixing user ID {$user->id} ({$user->name}): count {$user->referral_count} → {$user->actual_count}");
                
                // Update the user's referral count
                User::where('id', $user->id)->update(['referral_count' => $user->actual_count]);
                
                // Log the change
                Log::info("Fixed user referral count", [
                    'user_id' => $user->id,
                    'previous_count' => $user->referral_count,
                    'new_count' => $user->actual_count
                ]);
            }
            
            $this->info('Fixed incorrect referral counts.');
        } else {
            $this->info('All referral counts are correct.');
        }
    }
} 