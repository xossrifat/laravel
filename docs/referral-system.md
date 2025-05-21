# Referral System Documentation

This document explains how the referral system works in the Spin & Earn application.

## Overview

The referral system allows users to earn rewards in two ways:
1. A fixed reward when a new user signs up using their referral link
2. A percentage-based reward from all earnings of users they have referred

## Referral Types

### Fixed Referral Reward
When a new user signs up using a referral link or code, the referrer receives a fixed amount of coins (configurable in admin settings).

### Percentage-Based Referral Reward
The referrer also earns a percentage of all coins that their referred users earn from various activities:
- Spinning the wheel
- Watching video ads  
- Completing shortlinks

## Admin Configuration

Administrators can configure the referral system through the Settings page:

1. **Referral Reward (Fixed)**: The fixed amount of coins a referrer receives when someone signs up using their link.
2. **Referral Percentage**: The percentage of coins that referrers earn from their referred users' activities.

## Telegram Integration

The referral system also works through Telegram:

1. **Generating Referral Links**: Users can generate and share a special Telegram referral link that leads directly to the Telegram bot.
2. **Referral Flow**: When a user clicks a Telegram referral link, they are directed to start a conversation with the Telegram bot, which stores the referral code in their session.
3. **Completing Referral**: When the user creates an account through Telegram, the referral is automatically processed.

## Technical Implementation

The referral system uses the following components:

1. **ReferralService**: A service class that handles both fixed and percentage-based referrals.
2. **ReferralReward Model**: Stores information about all referral rewards, including whether they are fixed or percentage-based.
3. **Database Structure**: 
   - `referral_rewards` table with fields: `user_id`, `referral_id`, `coins_earned`, `is_percentage_reward`, `percentage_rate`, `source_activity`.
   - `users` table with fields: `referred_by`, `referral_code`, `referral_count`.

## API

### ReferralService

The `ReferralService` class provides two main methods:

#### processInitialReferral

Processes a fixed reward when a new user signs up using a referral code:

```php
public function processInitialReferral(User $user, string $referralCode): bool
```

#### processPercentageReward

Processes a percentage-based reward when a referred user earns coins:

```php
public function processPercentageReward(User $user, int $coinsEarned, string $activityType): bool
```

## Best Practices

1. Always use the `ReferralService` to process referrals.
2. Call `processPercentageReward` whenever a user earns coins through an activity.
3. Use the percentage rate from settings rather than hardcoding it. 