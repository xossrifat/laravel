<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shortlink extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'url',
        'coins',
        'active',
        'timer_duration',
        'max_claims',
        'rewarded',
        'daily_reset',
        'verification_code',
        'requires_verification',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'coins' => 'integer',
        'active' => 'boolean',
        'timer_duration' => 'integer',
        'max_claims' => 'integer',
        'rewarded' => 'boolean',
        'daily_reset' => 'boolean',
        'requires_verification' => 'boolean',
    ];

    /**
     * Get all users who have claimed this shortlink.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('last_claimed_at', 'verified')
                    ->withTimestamps();
    }
    
    /**
     * Check if a user has claimed this shortlink.
     * If daily_reset is true, checks if claimed today.
     * If daily_reset is false, checks if ever claimed.
     */
    public function hasUserClaimed($userId)
    {
        if ($this->daily_reset) {
            return $this->hasUserClaimedToday($userId);
        } else {
        return $this->users()->where('users.id', $userId)->exists();
        }
    }
    
    /**
     * Check if a user has claimed this shortlink today.
     */
    public function hasUserClaimedToday($userId)
    {
        return $this->users()
            ->where('users.id', $userId)
            ->whereDate('shortlink_user.last_claimed_at', today())
            ->exists();
    }
    
    /**
     * Check if a user has verified this shortlink.
     * For shortlinks that require verification.
     */
    public function hasUserVerified($userId)
    {
        return $this->users()
            ->where('users.id', $userId)
            ->where('shortlink_user.verified', true)
            ->exists();
    }

    /**
     * Get the number of unique users who have claimed this shortlink.
     * If daily_reset is true, only count claims for today.
     */
    public function claimCount()
    {
        $query = $this->users();
        
        // For daily reset links, only count today's claims
        if ($this->daily_reset) {
            $query->whereDate('shortlink_user.last_claimed_at', today());
        }
        
        return $query->count();
    }

    /**
     * Check if the max claims limit has been reached.
     */
    public function isClaimLimitReached()
    {
        if ($this->max_claims === null) {
            return false;
        }
        
        return $this->claimCount() >= $this->max_claims;
    }
    
    /**
     * Generate a random verification code if none exists
     */
    public function generateVerificationCode($length = 6)
    {
        if (empty($this->verification_code)) {
            $verificationCode = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, $length));
            
            // Update only the verification_code field instead of saving the entire model
            self::where('id', $this->id)->update(['verification_code' => $verificationCode]);
            
            // Update the current model instance
            $this->verification_code = $verificationCode;
        }
        
        return $this->verification_code;
    }
}
