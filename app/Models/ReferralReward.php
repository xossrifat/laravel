<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'referral_id',
        'coins_earned',
        'is_percentage_reward',
        'percentage_rate',
        'source_activity',
    ];

    protected $appends = ['type'];

    /**
     * Get the user who earned the reward.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the referred user.
     */
    public function referral()
    {
        return $this->belongsTo(User::class, 'referral_id');
    }
    
    /**
     * Get the reward type (fixed or percentage)
     */
    public function getTypeAttribute()
    {
        return $this->is_percentage_reward ? 'percentage' : 'fixed';
    }
} 