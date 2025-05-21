<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spin extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'coins_won',
        'reward_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'coins_won' => 'integer',
    ];

    /**
     * Get the user that owns the spin.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reward that was won.
     */
    public function reward()
    {
        return $this->belongsTo(SpinReward::class, 'reward_id');
    }
}
