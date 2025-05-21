<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->is_banned) {
            // If the user is trying to access the notifications or support pages, allow it
            if ($request->is('notifications*') || $request->is('support*')) {
                return $next($request);
            }
            
            // Get the most recent ban notification
            $banNotification = Auth::user()->notifications()
                ->where('type', 'ban')
                ->latest()
                ->first();
                
            // Redirect to a special page for banned users
            return redirect()->route('notifications.index')
                ->with('error', 'Your account has been suspended. Please check your notifications for more information.');
        }

        return $next($request);
    }
}
