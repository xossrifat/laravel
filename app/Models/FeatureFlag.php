<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureFlag extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'name',
        'description',
        'is_enabled',
        'config'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_enabled' => 'boolean',
        'config' => 'array',
    ];
    
    /**
     * নির্দিষ্ট কী দিয়ে ফিচার সক্রিয় কিনা যাচাই করে
     *
     * @param string $key
     * @return bool
     */
    public static function isEnabled(string $key): bool
    {
        return static::where('key', $key)
            ->where('is_enabled', true)
            ->exists();
    }
    
    /**
     * একটি ফিচার ফ্ল্যাগ সক্রিয় বা নিষ্ক্রিয় করে
     *
     * @param string $key
     * @param bool $value
     * @return bool
     */
    public static function toggle(string $key, bool $value): bool
    {
        $flag = static::where('key', $key)->first();
        
        if (!$flag) {
            return false;
        }
        
        $flag->is_enabled = $value;
        return $flag->save();
    }
}
