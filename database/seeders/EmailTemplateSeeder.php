<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;
use Illuminate\Support\Str;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Welcome Email
        EmailTemplate::create([
            'name' => 'Welcome to  RewardBazar',
            'slug' => 'welcome-email',
            'subject' => 'Welcome to  RewardBazar - Your Account is Ready!',
            'content' => '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 5px;">
                <div style="text-align: center; padding: 20px 0; border-bottom: 2px solid #f0f0f0;">
                    <h1 style="color: #4f46e5;">Welcome to  RewardBazar!</h1>
                </div>
                
                <div style="padding: 20px 0;">
                    <p>Dear {{userName}},</p>
                    
                    <p>Thank you for joining  RewardBazar! We are excited to have you as part of our community.</p>
                    
                    <p>Here\'s what you can do right away:</p>
                    
                    <ul style="padding-left: 20px;">
                        <li>Spin the wheel daily for free coins</li>
                        <li>Complete shortlinks to earn more</li>
                        <li>Watch videos for additional rewards</li>
                        <li>Redeem your earnings for real rewards</li>
                    </ul>
                    
                    <p>Your account details:</p>
                    <p><strong>Email:</strong> {{userEmail}}</p>
                    
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="{{loginLink}}" style="background-color: #4f46e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">Login to Your Account</a>
                    </div>
                    
                    <p>If you have any questions, feel free to contact our support team.</p>
                    
                    <p>Happy earning!</p>
                    <p>The  RewardBazar Team</p>
                </div>
                
                <div style="text-align: center; padding-top: 20px; border-top: 1px solid #f0f0f0; font-size: 12px; color: #666;">
                    <p>© 2023  RewardBazar. All rights reserved.</p>
                </div>
            </div>',
            'type' => 'general',
            'variables' => json_encode([
                'userName' => 'User\'s name',
                'userEmail' => 'User\'s email address',
                'loginLink' => 'Link to login page'
            ]),
            'active' => true
        ]);

        // 2. Reward Notification
        EmailTemplate::create([
            'name' => 'Reward Earned Notification',
            'slug' => 'reward-notification',
            'subject' => 'Congratulations! You\'ve Earned a Reward on  RewardBazar',
            'content' => '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 5px;">
                <div style="text-align: center; padding: 20px 0; border-bottom: 2px solid #f0f0f0;">
                    <h1 style="color: #4f46e5;">🎉 Reward Earned! 🎉</h1>
                </div>
                
                <div style="padding: 20px 0;">
                    <p>Hello {{userName}},</p>
                    
                    <p>Great news! You\'ve just earned a reward on  RewardBazar.</p>
                    
                    <div style="background-color: #f8f9fa; border-left: 4px solid #4f46e5; padding: 15px; margin: 20px 0;">
                        <p style="font-size: 18px; margin: 0;"><strong>Reward Details:</strong></p>
                        <p style="font-size: 16px; margin: 10px 0 0;"><strong>{{rewardName}}</strong></p>
                        <p style="margin: 5px 0 0;">{{rewardDescription}}</p>
                        <p style="margin: 10px 0 0;"><strong>Amount:</strong> {{rewardAmount}} {{rewardCurrency}}</p>
                    </div>
                    
                    <p>This reward has been added to your account balance. You can view your updated balance by logging into your  RewardBazar dashboard.</p>
                    
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="{{dashboardLink}}" style="background-color: #4f46e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">View Your Dashboard</a>
                    </div>
                    
                    <p>Keep spinning, keep earning!</p>
                    <p>The  RewardBazar Team</p>
                </div>
                
                <div style="text-align: center; padding-top: 20px; border-top: 1px solid #f0f0f0; font-size: 12px; color: #666;">
                    <p>© 2023  RewardBazar. All rights reserved.</p>
                </div>
            </div>',
            'type' => 'reward',
            'variables' => json_encode([
                'userName' => 'User\'s name',
                'rewardName' => 'Name of the earned reward',
                'rewardDescription' => 'Description of the reward',
                'rewardAmount' => 'Amount of coins/currency earned',
                'rewardCurrency' => 'Type of currency (coins, points, etc.)',
                'dashboardLink' => 'Link to user dashboard'
            ]),
            'active' => true
        ]);

        // 3. Shortlink Reward
        EmailTemplate::create([
            'name' => 'Shortlink Reward',
            'slug' => 'shortlink-reward',
            'subject' => 'You\'ve Earned Rewards from Completing a Shortlink!',
            'content' => '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 5px;">
                <div style="text-align: center; padding: 20px 0; border-bottom: 2px solid #f0f0f0;">
                    <h1 style="color: #4f46e5;">Shortlink Reward!</h1>
                </div>
                
                <div style="padding: 20px 0;">
                    <p>Hello {{userName}},</p>
                    
                    <p>Congratulations! You\'ve successfully completed a shortlink task and earned rewards.</p>
                    
                    <div style="background-color: #f8f9fa; border-left: 4px solid #4f46e5; padding: 15px; margin: 20px 0;">
                        <p style="font-size: 18px; margin: 0;"><strong>Reward Details:</strong></p>
                        <p style="margin: 10px 0 0;"><strong>Shortlink:</strong> {{shortlinkName}}</p>
                        <p style="margin: 10px 0 0;"><strong>Reward:</strong> {{rewardAmount}} {{rewardCurrency}}</p>
                        <p style="margin: 10px 0 0;"><strong>Completed:</strong> {{completionDate}}</p>
                    </div>
                    
                    <p>Your account has been credited with the reward. Continue completing more shortlinks to earn additional rewards!</p>
                    
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="{{shortlinksLink}}" style="background-color: #4f46e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">Complete More Shortlinks</a>
                    </div>
                    
                    <p>Thank you for using  RewardBazar!</p>
                    <p>The  RewardBazar Team</p>
                </div>
                
                <div style="text-align: center; padding-top: 20px; border-top: 1px solid #f0f0f0; font-size: 12px; color: #666;">
                    <p>© 2023  RewardBazar. All rights reserved.</p>
                </div>
            </div>',
            'type' => 'reward',
            'variables' => json_encode([
                'userName' => 'User\'s name',
                'shortlinkName' => 'Name of the completed shortlink',
                'rewardAmount' => 'Amount of coins earned',
                'rewardCurrency' => 'Type of currency (coins, points, etc.)',
                'completionDate' => 'Date and time of completion',
                'shortlinksLink' => 'Link to shortlinks page'
            ]),
            'active' => true
        ]);

        // 4. Password Reset
        EmailTemplate::create([
            'name' => 'Password Reset',
            'slug' => 'password-reset',
            'subject' => 'Reset Your  RewardBazar Password',
            'content' => '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 5px;">
                <div style="text-align: center; padding: 20px 0; border-bottom: 2px solid #f0f0f0;">
                    <h1 style="color: #4f46e5;">Reset Your Password</h1>
                </div>
                
                <div style="padding: 20px 0;">
                    <p>Hello,</p>
                    
                    <p>We received a request to reset your password for your  RewardBazar account. If you didn\'t make this request, you can safely ignore this email.</p>
                    
                    <p>To reset your password, click the button below:</p>
                    
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="{{resetLink}}" style="background-color: #4f46e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">Reset Password</a>
                    </div>
                    
                    <p>Or copy and paste the following URL into your browser:</p>
                    <p style="word-break: break-all; background-color: #f8f9fa; padding: 10px; border-radius: 5px;">{{resetLink}}</p>
                    
                    <p>This password reset link will expire in 60 minutes.</p>
                    
                    <p>If you did not request a password reset, no further action is required.</p>
                    
                    <p>Regards,<br>The  RewardBazar Team</p>
                </div>
                
                <div style="text-align: center; padding-top: 20px; border-top: 1px solid #f0f0f0; font-size: 12px; color: #666;">
                    <p>© 2023  RewardBazar. All rights reserved.</p>
                </div>
            </div>',
            'type' => 'system',
            'variables' => json_encode([
                'resetLink' => 'Password reset link'
            ]),
            'active' => true
        ]);
        
        // 5. Shortlink Update Notification
        EmailTemplate::create([
            'name' => 'Shortlink Update Notification',
            'slug' => 'shortlink-update',
            'subject' => 'New Shortlink Available on  RewardBazar!',
            'content' => '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 5px;">
                <div style="text-align: center; padding: 20px 0; border-bottom: 2px solid #f0f0f0;">
                    <h1 style="color: #4f46e5;">New Earning Opportunity!</h1>
                </div>
                
                <div style="padding: 20px 0;">
                    <p>Hello {{userName}},</p>
                    
                    <p>Good news! A shortlink has been updated and is now available for you to earn more coins!</p>
                    
                    <div style="background-color: #f8f9fa; border-left: 4px solid #4f46e5; padding: 15px; margin: 20px 0;">
                        <p style="font-size: 18px; margin: 0;"><strong>Shortlink Details:</strong></p>
                        <p style="font-size: 16px; margin: 10px 0 0;"><strong>{{shortlinkName}}</strong></p>
                        <p style="margin: 10px 0 0;"><strong>Reward:</strong> {{rewardAmount}} {{rewardCurrency}}</p>
                    </div>
                    
                    <p>Complete this shortlink to earn rewards. If you\'ve already claimed this link before, check if it\'s now available for daily claims!</p>
                    
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="{{shortlinksLink}}" style="background-color: #4f46e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">Complete Shortlinks Now</a>
                    </div>
                    
                    <p>Thank you for using  RewardBazar!</p>
                    <p>The  RewardBazar Team</p>
                </div>
                
                <div style="text-align: center; padding-top: 20px; border-top: 1px solid #f0f0f0; font-size: 12px; color: #666;">
                    <p>© 2023  RewardBazar. All rights reserved.</p>
                </div>
            </div>',
            'type' => 'notification',
            'variables' => json_encode([
                'userName' => 'User\'s name',
                'shortlinkName' => 'Name of the shortlink',
                'rewardAmount' => 'Amount of coins offered',
                'rewardCurrency' => 'Type of currency (coins, points, etc.)',
                'shortlinksLink' => 'Link to shortlinks page'
            ]),
            'active' => true
        ]);
    }
} 