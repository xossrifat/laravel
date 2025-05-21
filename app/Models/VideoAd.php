<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoAd extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'content',
        'type',
        'priority',
        'is_active'
    ];
    
    /**
     * Get a random ad based on priority weighting
     *
     * @return VideoAd|null
     */
    public static function getRandomAd()
    {
        // Get all active ads
        $ads = self::where('is_active', true)->get();
        
        if ($ads->isEmpty()) {
            return null;
        }
        
        // If there's only one ad, return it
        if ($ads->count() === 1) {
            return $ads->first();
        }
        
        // Calculate total priority
        $totalPriority = $ads->sum('priority');
        
        // If no priority is set (sum is 0), treat all ads equally
        if ($totalPriority <= 0) {
            return $ads->random();
        }
        
        // Generate a random number between 1 and total priority
        $randomValue = rand(1, $totalPriority);
        
        // Find the ad that corresponds to the random value based on priority
        $cumulativePriority = 0;
        foreach ($ads as $ad) {
            $cumulativePriority += $ad->priority;
            if ($randomValue <= $cumulativePriority) {
                return $ad;
            }
        }
        
        // Fallback - return the first ad
        return $ads->first();
    }
}
