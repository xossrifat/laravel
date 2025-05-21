<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\FeatureFlag;

class CheckFeatureEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $featureKey
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $featureKey)
    {
        // যাচাই করুন ফিচারটি সক্রিয় আছে কিনা
        $isEnabled = FeatureFlag::isEnabled($featureKey);
        
        if (!$isEnabled) {
            return redirect()->route('coming-soon');
        }
        
        return $next($request);
    }
} 