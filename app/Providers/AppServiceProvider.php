<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

     public const HOME = '/dashboard'; // 👈 change this if it's '/home'

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Observers
        \App\Models\User::observe(\App\Observers\CoinEarningObserver::class);
        \App\Models\Notification::observe(\App\Observers\NotificationObserver::class);
        
        // Force HTTPS for URLs in all environments except local development
        if ($this->app->environment('production') || request()->server('HTTPS') == 'on' || 
            !in_array(request()->getHost(), ['localhost', '127.0.0.1'])) {
            URL::forceScheme('https');
            // Set secure cookies when using HTTPS
            config(['session.secure' => true]);
            config(['session.same_site' => 'none']);
        }
        
        // Always include Ngrok's X-Forwarded headers when detected
        if ($this->app->runningInConsole() == false && request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
            // Important for ngrok tunnels
            $this->app['request']->server->set('HTTPS', 'on');
        }
    }
}
