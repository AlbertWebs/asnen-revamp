<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingUpdateRequest;
use App\Models\Setting;
use App\Services\Settings;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(string $group): View
    {
        abort_unless(auth()->user()?->can('settings.update'), 403);

        $settings = Setting::query()
            ->where('group', $group)
            ->orderBy('key')
            ->get();

        return view('admin.settings.edit', compact('group', 'settings'));
    }

    public function update(SettingUpdateRequest $request, string $group): RedirectResponse
    {
        abort_unless(auth()->user()?->can('settings.update'), 403);

        foreach ($request->input('settings', []) as $item) {
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

        app(Settings::class)->forget();

        return back()->with('success', 'Settings saved.');
    }
}
