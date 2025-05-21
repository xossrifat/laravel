<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     */
    protected $middleware = [
        // Force HTTPS connections
        \App\Http\Middleware\ForceHttps::class,
        
        // Handle proxies
        \Illuminate\Http\Middleware\TrustProxies::class,

        // Handle CORS
        \Fruitcake\Cors\HandleCors::class,

        // Prevent requests during maintenance
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,

        // Validate post size
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,

        // Trim strings
        \App\Http\Middleware\TrimStrings::class,

        // Convert empty strings to null
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\TrackLastLoginAt::class,
            \App\Http\Middleware\ShareAdSettings::class,
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // Add custom middleware here, e.g. admin auth
        
        'admin' => \App\Http\Middleware\AdminAuth::class,
        'check.banned' => \App\Http\Middleware\CheckUserBanned::class,
        'feature.enabled' => \App\Http\Middleware\CheckFeatureEnabled::class,
        'prevent.production' => \App\Http\Middleware\PreventInProduction::class,
    ];

    /**
     * The application's middleware aliases.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
                'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,        'check.banned' => \App\Http\Middleware\CheckUserBanned::class,        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'feature.enabled' => \App\Http\Middleware\CheckFeatureEnabled::class,
    ];
}
