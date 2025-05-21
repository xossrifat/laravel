<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckMobileVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:check-mobile-verification {--unverified : Show only unverified users} {--verified : Show only verified users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check which users have verified their mobile numbers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = User::query();
        
        if ($this->option('unverified')) {
            $query->whereNull('mobile_verified_at');
            $this->info('Showing only unverified users...');
        } elseif ($this->option('verified')) {
            $query->whereNotNull('mobile_verified_at');
            $this->info('Showing only verified users...');
        } else {
            $this->info('Showing all users...');
        }
        
        $users = $query->select(['id', 'name', 'email', 'mobile_number', 'mobile_verified_at', 'preferred_otp_channel'])
            ->get();
            
        if ($users->isEmpty()) {
            $this->warn('No users found matching the criteria.');
            return;
        }
        
        // Prepare data for table
        $data = $users->map(function ($user) {
            return [
                'ID' => $user->id,
                'Name' => $user->name,
                'Email' => $user->email,
                'Mobile' => $user->mobile_number ?: 'Not set',
                'Verified' => $user->mobile_verified_at ? 'Yes ✓' : 'No ✗',
                'Verification Date' => $user->mobile_verified_at ? $user->mobile_verified_at->format('Y-m-d H:i:s') : 'N/A',
                'OTP Channel' => $user->preferred_otp_channel ?: 'Not set'
            ];
        })->toArray();
        
        // Show stats
        $totalUsers = $users->count();
        $verifiedUsers = $users->filter(function ($user) {
            return !empty($user->mobile_verified_at);
        })->count();
        $unverifiedUsers = $totalUsers - $verifiedUsers;
        
        $this->table(
            ['ID', 'Name', 'Email', 'Mobile', 'Verified', 'Verification Date', 'OTP Channel'],
            $data
        );
        
        $this->info("Total users: {$totalUsers}");
        $this->info("Verified users: {$verifiedUsers} (" . round(($verifiedUsers / max(1, $totalUsers)) * 100, 2) . "%)");
        $this->info("Unverified users: {$unverifiedUsers} (" . round(($unverifiedUsers / max(1, $totalUsers)) * 100, 2) . "%)");
    }
} 