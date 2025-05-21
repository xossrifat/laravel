<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateReferralCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:generate-referral-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate referral codes for existing users that do not have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to generate referral codes for users without them...');
        
        // Get all users that don't have a referral code
        $users = User::whereNull('referral_code')->orWhere('referral_code', '')->get();
        
        $count = $users->count();
        if ($count === 0) {
            $this->info('All users already have referral codes! Nothing to do.');
            return;
        }
        
        $this->info("Found {$count} users without referral codes.");
        $bar = $this->output->createProgressBar($count);
        $bar->start();
        
        foreach ($users as $user) {
            $user->referral_code = User::generateUniqueReferralCode();
            $user->save();
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info("Successfully generated referral codes for {$count} users!");
    }
} 