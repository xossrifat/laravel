<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Get a setting value by key and decode it from JSON.
     *
     * @param string $key
     * @param mixed $default
     * @return array|null
     */
    public static function getJson(string $key, $default = null)
    {
        $value = static::get($key);
        
        if ($value === null) {
            return $default;
        }
        
        try {
            $decoded = json_decode($value, true);
            
            // If the value couldn't be decoded or is not an array, return default
            if ($decoded === null || !is_array($decoded)) {
                \Log::warning("Failed to decode JSON setting: {$key}");
                return $default;
            }
            
            return $decoded;
        } catch (\Exception $e) {
            \Log::error("Error decoding JSON setting {$key}: " . $e->getMessage());
            return $default;
        }
    }

    /**
     * Set a setting value.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set(string $key, $value)
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get all ad-related settings
     */
    public static function getAdSettings()
    {
        try {
            $settings = [
                'global_ads_enabled' => (bool)self::get('global_ads_enabled', false),
                'global_native_ad_enabled' => (bool)self::get('global_native_ad_enabled', false),
                'global_banner_ad_enabled' => (bool)self::get('global_banner_ad_enabled', false),
                'global_social_ad_enabled' => (bool)self::get('global_social_ad_enabled', false),
                'left_sidebar_ad_enabled' => (bool)self::get('left_sidebar_ad_enabled', false),
                'right_sidebar_ad_enabled' => (bool)self::get('right_sidebar_ad_enabled', false),
                'global_native_ad_code' => self::get('global_native_ad_code', ''),
                'global_banner_ad_code' => self::get('global_banner_ad_code', ''),
                'global_social_ad_code' => self::get('global_social_ad_code', ''),
                'right_sidebar_ad_code' => self::get('right_sidebar_ad_code', ''),
            ];
            
            // Ensure JavaScript code is properly preserved
            foreach (['global_banner_ad_code', 'global_native_ad_code', 'global_social_ad_code', 'right_sidebar_ad_code'] as $codeKey) {
                if (!empty($settings[$codeKey])) {
                    // Make sure script tags are properly preserved
                    $settings[$codeKey] = html_entity_decode($settings[$codeKey], ENT_QUOTES);
                }
            }
            
            // Log the sidebar ad settings for debugging
            \Log::info('Getting ad settings from database', [
                'right_sidebar_enabled' => $settings['right_sidebar_ad_enabled'],
                'right_sidebar_ad_code_exists' => !empty($settings['right_sidebar_ad_code']),
                'global_ads_enabled' => $settings['global_ads_enabled']
            ]);
            
            return $settings;
        } catch (\Exception $e) {
            \Log::error('Error getting ad settings: ' . $e->getMessage());
            return [];
        }
    }
} 