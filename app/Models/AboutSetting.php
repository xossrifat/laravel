<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_name',
        'app_version',
        'app_tagline',
        'app_description',
        'features_json',
        'support_email',
        'live_chat_available',
        'terms_url',
        'privacy_url',
        'cookie_url',
        'logo_path'
    ];

    protected $casts = [
        'features_json' => 'array',
        'live_chat_available' => 'boolean',
    ];

    /**
     * Get the features as an array
     */
    public function getFeatures()
    {
        return $this->features_json ?? [
            [
                'title' => 'Daily Spins',
                'description' => 'Spin the wheel daily to earn random coin rewards',
                'icon' => 'fa-sync-alt'
            ],
            [
                'title' => 'Referral Program',
                'description' => 'Invite friends and earn commissions on their earnings',
                'icon' => 'fa-users'
            ],
            [
                'title' => 'Tasks & Offers',
                'description' => 'Complete tasks and offers to earn additional coins',
                'icon' => 'fa-tasks'
            ],
            [
                'title' => 'Withdrawals',
                'description' => 'Convert your coins to real money and withdraw to your preferred payment method',
                'icon' => 'fa-wallet'
            ]
        ];
    }

    /**
     * Get the current about settings or create default
     */
    public static function getCurrentSettings()
    {
        $settings = self::first();
        
        if (!$settings) {
            $settings = self::create([
                'app_name' => 'Spin & Earn',
                'app_version' => '1.0.0',
                'app_tagline' => 'The fun way to earn rewards!',
                'app_description' => 'Spin & Earn is a rewards platform that allows users to earn coins by completing simple tasks, spinning the wheel, and referring friends. These coins can be converted to real money and withdrawn to your preferred payment method.',
                'support_email' => 'support@spinandearnn.com',
                'live_chat_available' => true,
                'terms_url' => '#',
                'privacy_url' => '#',
                'cookie_url' => '#',
            ]);
        }
        
        return $settings;
    }
} 