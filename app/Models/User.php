<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'coins',
        'is_banned',
        'last_login_at',
        'phone',
        'address',
        'theme',
        'notify_rewards',
        'notify_updates',
        'notify_withdrawals',
        'telegram_id',
        'telegram_username',
        'is_telegram_user',
        'referral_code',
        'referred_by',
        'referral_count',
        'mobile_number',
        'is_mobile_verified',
        'mobile_verified_at',
        'mobile_verification_code',
        'preferred_otp_channel',
        'payout_email'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'coins' => 'integer',
        'is_banned' => 'boolean',
        'last_login_at' => 'datetime',
        'theme' => 'string',
        'notify_rewards' => 'boolean',
        'notify_updates' => 'boolean',
        'notify_withdrawals' => 'boolean',
        'referral_count' => 'integer',
        'is_mobile_verified' => 'boolean',
        'mobile_verified_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        // Auto-generate a referral code when creating a user if not provided
        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = static::generateUniqueReferralCode();
            }
        });
    }
    
    /**
     * Generate a unique referral code.
     */
    public static function generateUniqueReferralCode()
    {
        do {
            $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        } while (static::where('referral_code', $code)->exists());
        
        return $code;
    }

    /**
     * Get all spins for the user.
     */
    public function spins()
    {
        return $this->hasMany(Spin::class);
    }

    /**
     * Get all video watches for the user.
     */
    public function videoWatches()
    {
        return $this->hasMany(VideoWatch::class);
    }

    /**
     * Get all withdrawals for the user.
     */
    public function withdrawals()
    {
        return $this->hasMany(Withdraw::class);
    }

    /**
     * Get all support messages for the user.
     */
    public function supportMessages()
    {
        return $this->hasMany(SupportMessage::class);
    }

    /**
     * Get all notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get all chat messages for the user.
     */
    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    /**
     * Get the shortlinks claimed by the user
     */
    public function shortlinks()
    {
        return $this->belongsToMany(Shortlink::class)
            ->withPivot('created_at', 'last_claimed_at')
            ->withTimestamps();
    }

    /**
     * Get the user who referred this user.
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    /**
     * Get the users that were referred by this user.
     */
    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    /**
     * Get all referral rewards earned by this user.
     */
    public function referralRewards()
    {
        return $this->hasMany(ReferralReward::class);
    }

    /**
     * Calculate total coins earned from referrals.
     */
    public function getTotalReferralCoinsAttribute()
    {
        return $this->referralRewards()->sum('coins_earned');
    }

    /**
     * Get today's spins count.
     */
    public function getTodaySpinsCountAttribute()
    {
        return $this->spins()
                    ->whereDate('created_at', today())
                    ->count();
    }

    /**
     * Get today's video watches count.
     */
    public function getTodayVideoWatchesCountAttribute()
    {
        return $this->videoWatches()
                    ->whereDate('created_at', today())
                    ->count();
    }

    /**
     * Get pending withdrawals.
     */
    public function getPendingWithdrawalsAttribute()
    {
        return $this->withdrawals()
                    ->where('status', 'pending')
                    ->get();
    }

    /**
     * Add coins to user's balance
     */
    public function addCoins(int $amount)
    {
        $this->coins += $amount;
        $this->save();
        return $this->coins;
    }

    /**
     * Remove coins from user's balance.
     */
    public function removeCoins($amount)
    {
        if ($this->coins >= $amount) {
            $this->decrement('coins', $amount);
            return true;
        }
        return false;
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }
    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            if (Auth::guard('admin')->user()->is_admin) {
                return redirect()->route('admin.dashboard');
            } else {
                Auth::guard('admin')->logout();
                return back()->withErrors(['email' => 'Unauthorized access.']);
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }
}
