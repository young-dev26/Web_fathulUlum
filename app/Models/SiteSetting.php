<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    public static function value(string $key, mixed $default = null): mixed
    {
        if (! Schema::hasTable('site_settings')) {
            return $default;
        }

        $setting = static::where('key', $key)->first();

        if (! $setting) {
            return $default;
        }

        return match ($setting->type) {
            'number' => (int) $setting->value,
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOL),
            'json' => json_decode($setting->value ?: 'null', true),
            default => $setting->value,
        };
    }
}