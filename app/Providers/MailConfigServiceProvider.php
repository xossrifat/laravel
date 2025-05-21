<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Only run if the settings table exists
        if (Schema::hasTable('settings')) {
            $this->configureMailSettings();
        }
    }
    
    /**
     * Configure mail settings from database
     */
    protected function configureMailSettings(): void
    {
        try {
            // If mail is not enabled, use the default config
            if (!Setting::get('mail_enabled', false)) {
                return;
            }
            
            $config = [
                'driver' => Setting::get('mail_mailer', Config::get('mail.default')),
                'host' => Setting::get('mail_host', Config::get('mail.mailers.smtp.host')),
                'port' => Setting::get('mail_port', Config::get('mail.mailers.smtp.port')),
                'from' => [
                    'address' => Setting::get('mail_from_address', Config::get('mail.from.address')),
                    'name' => Setting::get('mail_from_name', Config::get('mail.from.name')),
                ],
                'encryption' => Setting::get('mail_encryption', Config::get('mail.mailers.smtp.encryption')),
                'username' => Setting::get('mail_username', Config::get('mail.mailers.smtp.username')),
                'password' => Setting::get('mail_password', Config::get('mail.mailers.smtp.password')),
            ];
            
            // Set the default mailer
            Config::set('mail.default', $config['driver']);
            
            // Set the SMTP configuration
            Config::set('mail.mailers.smtp.host', $config['host']);
            Config::set('mail.mailers.smtp.port', $config['port']);
            Config::set('mail.mailers.smtp.encryption', $config['encryption']);
            Config::set('mail.mailers.smtp.username', $config['username']);
            Config::set('mail.mailers.smtp.password', $config['password']);
            
            // Set the global "from" address and name
            Config::set('mail.from.address', $config['from']['address']);
            Config::set('mail.from.name', $config['from']['name']);
            
        } catch (\Exception $e) {
            // Log error but don't crash the application
            logger()->error('Failed to load mail configuration: ' . $e->getMessage());
        }
    }
}
