<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force HTTPS in production or when explicitly enabled
        if ((App::environment('production') || env('FORCE_HTTPS', false)) && !$request->secure()) {
            // Check if using load balancer/proxy with HTTP_X_FORWARDED_PROTO header
            if ($request->header('X-Forwarded-Proto') !== 'https') {
                // Skip redirect for localhost/development
                if (!in_array($request->getHost(), ['localhost', '127.0.0.1'])) {
                    return redirect()->secure($request->getRequestUri());
                }
            }
        }

        // Add security headers
        $response = $next($request);
        
        if (!$response instanceof Response) {
            return $response;
        }
        
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Set Content Security Policy to prevent mixed content
        $response->headers->set(
            'Content-Security-Policy',
            "default-src https: 'self'; " .
            "script-src https: 'self' 'unsafe-inline' 'unsafe-eval' https://solseewuthi.net; " .
            "style-src https: 'self' 'unsafe-inline'; " .
            "img-src https: data: 'self'; " .
            "connect-src https: 'self'; " .
            "font-src https: data: 'self'; " .
            "object-src 'none'; " .
            "media-src https: 'self'; " .
            "frame-src https: 'self'; " .
            "upgrade-insecure-requests;"
        );

        return $response;
    }
} 