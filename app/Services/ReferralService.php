<?php

namespace App\Services;

use App\Models\ReferralReward;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ReferralService
{
    /**
     * Process a fixed reward when a user is referred.
     *
     * @param User $user The referred user
     * @param string $referralCode The referral code used
     * @return bool Whether the referral was successful
     */
    public function processInitialReferral(User $user, string $referralCode): bool
    {
        // Log the attempt with detailed information
        Log::info('Processing initial referral', [
            'referred_user_id' => $user->id, 
            'referral_code' => $referralCode,
            'user_has_referrer' => (bool)$user->referred_by
        ]);
        
        // Find the referring user by referral code
        $referrer = User::where('referral_code', $referralCode)->first();
        
        if (!$referrer) {
            Log::warning('Referral failed: Invalid referral code', [
                'referral_code' => $referralCode
            ]);
            return false;
        }
        
        // Check if user is referring themselves
        if ($user->id === $referrer->id) {
            Log::warning('Referral failed: User tried to refer themselves', [
                'user_id' => $user->id,
                'referral_code' => $referralCode
            ]);
            return false;
        }
        
        // Check if user has already been referred
        if ($user->referred_by) {
            Log::warning('Referral failed: User already has a referrer', [
                'user_id' => $user->id,
                'existing_referrer' => $user->referred_by,
                'attempted_referrer' => $referrer->id
            ]);
            return false;
        }
        
        // Set the referrer
        $user->referred_by = $referrer->id;
        $user->save();
        
        // Increment referrer's referral count
        $referrer->increment('referral_count');
        
        // Get referral reward amount from settings
        $rewardAmount = Setting::where('key', 'referral_reward')->first()?->value ?? 100;
        
        // Award coins to the referrer and record the reward
        $referrer->addCoins($rewardAmount);
        
        ReferralReward::create([
            'user_id' => $referrer->id,
            'referral_id' => $user->id,
            'coins_earned' => $rewardAmount,
            'is_percentage_reward' => false,
            'source_activity' => 'signup'
        ]);
        
        // Add notification for the referrer
        $referrer->notifications()->create([
            'title' => 'New Referral Reward!',
            'message' => "You earned {$rewardAmount} coins from referring {$user->name}!",
            'type' => 'referral'
        ]);
        
        Log::info('Referral processed successfully', [
            'referrer_id' => $referrer->id,
            'referred_user_id' => $user->id,
            'reward_amount' => $rewardAmount
        ]);
        
        return true;
    }
    
    /**
     * Process percentage-based reward when a referred user earns coins.
     *
     * @param User $user The user who earned coins
     * @param int $coinsEarned The number of coins earned
     * @param string $activityType The type of activity that earned coins
     * @return bool Whether a referral reward was issued
     */
    public function processPercentageReward(User $user, int $coinsEarned, string $activityType): bool
    {
        // Log the start of the process with all inputs
        Log::info("Starting percentage referral reward process", [
            'user_id' => $user->id, 
            'user_name' => $user->name,
            'coins_earned' => $coinsEarned,
            'activity_type' => $activityType,
            'has_referrer' => (bool)$user->referred_by
        ]);
        
        // Check if user was referred by someone
        if (!$user->referred_by) {
            Log::info("No referral reward processed - user has no referrer", [
                'user_id' => $user->id
            ]);
            return false;
        }
        
        // Get the referrer
        $referrer = User::find($user->referred_by);
        
        if (!$referrer) {
            Log::warning("No referral reward processed - referrer not found", [
                'user_id' => $user->id,
                'referrer_id' => $user->referred_by
            ]);
            return false;
        }
        
        // Get referral percentage from settings
        $referralPercentage = (float) (Setting::where('key', 'referral_percentage')->first()?->value ?? 5);
        
        // Calculate reward amount
        $percentageReward = (int) ceil($coinsEarned * ($referralPercentage / 100));
        
        Log::info("Calculated referral reward", [
            'coins_earned' => $coinsEarned,
            'percentage' => $referralPercentage,
            'reward_amount' => $percentageReward
        ]);
        
        // Only process if there's a reward to give
        if ($percentageReward <= 0) {
            Log::info("No referral reward processed - calculated reward is zero", [
                'percentage' => $referralPercentage,
                'coins_earned' => $coinsEarned
            ]);
            return false;
        }
        
        // Award coins to the referrer and record the reward
        try {
            $referrer->addCoins($percentageReward);
            
            ReferralReward::create([
                'user_id' => $referrer->id,
                'referral_id' => $user->id,
                'coins_earned' => $percentageReward,
                'is_percentage_reward' => true,
                'percentage_rate' => $referralPercentage,
                'source_activity' => $activityType
            ]);
            
            Log::info("Referral reward recorded in database", [
                'referrer_id' => $referrer->id,
                'user_id' => $user->id,
                'coins_earned' => $percentageReward,
                'is_percentage' => true,
                'activity' => $activityType
            ]);
            
            // Remove notification creation - we don't want notifications for percentage rewards
            // Only initial referral rewards will have notifications
            
            Log::info("Referral percentage reward processed successfully", [
                'referrer_id' => $referrer->id, 
                'user_id' => $user->id,
                'activity' => $activityType,
                'coins_earned' => $coinsEarned,
                'percentage' => $referralPercentage,
                'reward' => $percentageReward
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error("Error processing referral reward", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
    
    /**
     * Generate a shareable Telegram referral link
     * 
     * @param User $user The user who owns the referral link
     * @return string The shareable link
     */
    public function generateTelegramReferralLink(User $user): string
    {
        // Get Telegram bot username from settings
        $botUsername = Setting::where('key', 'telegram_bot_username')->first()?->value ?? 'SmsSB_bot';
        
        // Format the referral link for Telegram Mini App - without the URL parameter
        // This format is compatible with Telegram Mini Apps and will properly pass the referral code
        return "https://t.me/{$botUsername}/app?startapp=referral_{$user->referral_code}";
    }
    
    /**
     * Get all users who were referred by a specific user
     *
     * @param User $referrer The referring user
     * @return \Illuminate\Database\Eloquent\Collection Users who were referred
     */
    public function getReferredUsers(User $referrer)
    {
        return User::where('referred_by', $referrer->id)->get();
    }
    
    /**
     * Get total earnings a user has made from referrals
     *
     * @param User $user The user to check
     * @return array Total earnings broken down by type
     */
    public function getTotalReferralEarnings(User $user): array
    {
        $fixed = ReferralReward::where('user_id', $user->id)
            ->where('is_percentage_reward', false)
            ->sum('coins_earned');
            
        $percentage = ReferralReward::where('user_id', $user->id)
            ->where('is_percentage_reward', true)
            ->sum('coins_earned');
            
        return [
            'fixed' => $fixed,
            'percentage' => $percentage,
            'total' => $fixed + $percentage
        ];
    }
} 