<?php

namespace App\Features\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['setting_key', 'setting_value'];

    /**
     * Get a setting value by key.
     * Use caching to avoid repeated DB queries.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever("setting.$key", function () use ($key, $default) {
            $setting = self::where('setting_key', $key)->first();
            return $setting ? $setting->setting_value : $default;
        });
    }

    /**
     * Update or create a setting.
     * Clears the cache for that key.
     *
     * @param string $key
     * @param string $value
     * @return self
     */
    public static function set(string $key, string $value)
    {
        $setting = self::updateOrCreate(
            ['setting_key' => $key],
            ['setting_value' => $value]
        );

        Cache::forget("setting.$key");
        Cache::forget("settings.all"); // Clear global settings cache

        return $setting;
    }

    /**
     * Get all settings as an associative array.
     *
     * @return array
     */
    public static function getAll()
    {
        return Cache::rememberForever("settings.all", function () {
            return self::pluck('setting_value', 'setting_key')->toArray();
        });
    }
}
