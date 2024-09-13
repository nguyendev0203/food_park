<?php

namespace App\Services;

use Cache;
use App\Models\Setting;

class SettingsService
{
    public function getSetting()
    {
        return Cache::rememberForever('settings', function () {
            return Setting::pluck('value', 'key')->toArray();
        });
    }

    public function setGlobalSettings(): void
    {
        $settings = $this->getSetting();
        config()->set('settings', $settings);
    }

    public function clearCachedSettings(): void
    {
        Cache::forget('settings');
    }
}
