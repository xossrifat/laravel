<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo users
        $users = [
            [
                'name' => 'Demo User 1',
                'email' => 'demo1@example.com',
                'password' => Hash::make('password'),
                'coins' => 500,
                'is_admin' => false,
                'is_banned' => false,
                'last_login_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Demo User 2',
                'email' => 'demo2@example.com',
                'password' => Hash::make('password'),
                'coins' => 1200,
                'is_admin' => false,
                'is_banned' => false,
                'last_login_at' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'name' => 'Demo User 3',
                'email' => 'demo3@example.com',
                'password' => Hash::make('password'),
                'coins' => 750,
                'is_admin' => false,
                'is_banned' => true,
                'last_login_at' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'name' => 'Demo User 4',
                'email' => 'demo4@example.com',
                'password' => Hash::make('password'),
                'coins' => 3000,
                'is_admin' => false,
                'is_banned' => false,
                'last_login_at' => Carbon::now(),
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
