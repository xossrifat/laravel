<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create {--name= : Rifat} {--email= : hirifat77@g.com} {--password= : 12345678} {--super : Set as super admin}';
    
    protected $description = 'Create a new admin user';

    public function handle()
    {
        $name = $this->option('name');
        $email = $this->option('email');
        $password = $this->option('password');
        $isSuperAdmin = $this->option('super');
        
        // If parameters are not provided, prompt for them
        if (!$name) {
            $name = $this->ask('Enter admin name');
        }
        
        if (!$email) {
            $email = $this->ask('Enter admin email');
        }
        
        if (!$password) {
            $password = $this->secret('Enter admin password');
        }
        
        // Validate input
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:8',
        ]);
        
        if ($validator->fails()) {
            $this->error('Validation failed:');
            foreach ($validator->errors()->all() as $error) {
                $this->error("- $error");
            }
            return 1;
        }
        
        // Create admin user
        $admin = Admin::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_super_admin' => $isSuperAdmin,
        ]);
        
        $this->info('Admin user created successfully!');
        $this->info("Name: {$admin->name}");
        $this->info("Email: {$admin->email}");
        $this->info("Super Admin: " . ($admin->is_super_admin ? 'Yes' : 'No'));
        
        return 0;
    }
} 