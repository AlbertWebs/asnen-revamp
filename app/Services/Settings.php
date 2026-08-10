<?php

namespace App\Services;

use App\Models\MediaAsset;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class Settings
{
    private const CACHE_KEY = 'site_settings';

    private const CACHE_TTL = 3600;

    public function get(string $key, mixed $default = null): mixed
    {
        $settings = $this->all();

        if (! array_key_exists($key, $settings)) {
            return $default;
        }

        $value = $settings[$key];

        if (is_array($value) && array_key_exists('value', $value)) {
            return $value['value'] ?? $default;
        }

        return $value ?? $default;
    }

    public function logoUrl(): string
    {
        $default = asset('brand/logo.png');
        $logoId = $this->get('brand.logo_id');

        if (! filled($logoId)) {
            return $default;
        }

        $asset = MediaAsset::query()->find((int) $logoId);

        return $asset?->publicUrl() ?: $default;
    }

    public function all(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Setting::query()
                ->where('is_public', true)
                ->pluck('value', 'key')
                ->all();
        });
    }

    public function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
