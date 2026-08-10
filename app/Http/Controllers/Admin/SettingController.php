<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingUpdateRequest;
use App\Models\MediaAsset;
use App\Models\Setting;
use App\Services\Settings;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(string $group): View
    {
        abort_unless(auth()->user()?->can('settings.update'), 403);

        if ($group === 'brand') {
            Setting::query()->firstOrCreate(
                ['key' => 'brand.logo_id'],
                [
                    'group' => 'brand',
                    'value' => ['value' => ''],
                    'is_public' => true,
                ]
            );
        }

        $settings = Setting::query()
            ->where('group', $group)
            ->orderBy('key')
            ->get()
            ->reject(fn (Setting $setting) => $setting->key === 'brand.logo_id')
            ->values();

        $logoId = null;
        $logoPreviewUrl = asset('brand/logo.png');
        if ($group === 'brand') {
            $logoSetting = Setting::query()->where('key', 'brand.logo_id')->first();
            $raw = is_array($logoSetting?->value) ? ($logoSetting->value['value'] ?? '') : ($logoSetting?->value ?? '');
            $logoId = filled($raw) ? (int) $raw : null;
            if ($logoId) {
                $asset = MediaAsset::query()->find($logoId);
                if ($asset?->publicUrl()) {
                    $logoPreviewUrl = $asset->publicUrl();
                }
            }
        }

        return view('admin.settings.edit', compact('group', 'settings', 'logoId', 'logoPreviewUrl'));
    }

    public function update(SettingUpdateRequest $request, string $group): RedirectResponse
    {
        abort_unless(auth()->user()?->can('settings.update'), 403);

        foreach ($request->input('settings', []) as $item) {
            if (($item['key'] ?? '') === 'brand.logo_id') {
                continue;
            }

            Setting::query()->updateOrCreate(
                ['key' => $item['key'], 'group' => $group],
                [
                    'value' => is_array($item['value'] ?? null)
                        ? $item['value']
                        : ['value' => $item['value'] ?? ''],
                    'is_public' => (bool) ($item['is_public'] ?? true),
                ]
            );
        }

        if ($group === 'brand') {
            $logoId = $request->input('logo_id');
            Setting::query()->updateOrCreate(
                ['key' => 'brand.logo_id', 'group' => 'brand'],
                [
                    'value' => ['value' => filled($logoId) ? (string) $logoId : ''],
                    'is_public' => true,
                ]
            );
        }

        app(Settings::class)->forget();

        return back()->with('success', 'Settings saved.');
    }
}
