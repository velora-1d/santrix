<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'description'];

    /**
     * Get a setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $cacheKey = "setting_{$key}";

        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();

            if (!$setting) {
                return $default;
            }

            // Cast value based on type
            return match ($setting->type) {
                'number' => (int) $setting->value,
                'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                'json' => json_decode($setting->value, true),
                default => $setting->value,
            };
        });
    }

    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public static function set(string $key, $value): bool
    {
        $setting = self::where('key', $key)->first();

        if ($setting) {
            $setting->value = is_array($value) ? json_encode($value) : $value;
            $setting->save();
        } else {
            self::create([
                'key' => $key,
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => is_numeric($value) ? 'number' : (is_bool($value) ? 'boolean' : (is_array($value) ? 'json' : 'string')),
            ]);
        }

        // Clear cache
        Cache::forget("setting_{$key}");

        return true;
    }

    /**
     * Get all settings by group
     *
     * @param string $group
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllByGroup(string $group)
    {
        return self::where('group', $group)->all();
    }
}

