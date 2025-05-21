<?php

namespace App\Observers;

use App\Models\User;
use App\Services\ReferralService;

class CoinEarningObserver
{
    protected $referralService;
    
    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }
    
    /**
     * Handle the User "updated" event.
     *
     * This observer listens for any user coins balance changes
     * and processes referral percentage rewards if applicable.
     */
    public function updated(User $user)
    {
        // Get the original model values before update
        $original = $user->getOriginal();
        
        // Check if coins were updated
        if (isset($original['coins']) && $user->coins > $original['coins']) {
            // Calculate how many coins were added
            $coinsAdded = $user->coins - $original['coins'];
            
            // Process referral percentage reward
            $this->referralService->processPercentageReward(
                $user, 
                $coinsAdded, 
                'automatic_observer'
            );
        }
    }
} 