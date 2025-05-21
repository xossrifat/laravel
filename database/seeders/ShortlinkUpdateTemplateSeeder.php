<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\DB;

class ShortlinkUpdateTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the template already exists
        $exists = EmailTemplate::where('slug', 'shortlink-update')->exists();
        
        if (!$exists) {
            // Add the Shortlink Update Notification template
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
            
            $this->command->info('Shortlink Update template created successfully.');
        } else {
            $this->command->info('Shortlink Update template already exists, skipping.');
        }
    }
} 