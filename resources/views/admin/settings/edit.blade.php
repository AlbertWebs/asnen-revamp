@extends('layouts.admin')

@section('title', 'Settings: '.ucfirst($group))
@section('heading', 'Settings: '.ucfirst($group))

@section('content')
    <div class="mb-4 flex flex-wrap gap-2">
        @foreach (['brand', 'contact', 'social', 'features', 'seo', 'website'] as $tab)
            <a
                href="{{ route('admin.settings.edit', $tab) }}"
                class="rounded-md px-3 py-1.5 text-sm font-medium {{ $group === $tab ? 'bg-forest-700 text-white' : 'border border-charcoal-300 bg-white text-charcoal-700 hover:bg-charcoal-50' }}"
            >
                {{ ucfirst($tab) }}
            </a>
        @endforeach
    </div>

    <form method="POST" action="{{ route('admin.settings.update', $group) }}" class="max-w-3xl rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
        @csrf
        @method('PUT')

        @if ($group === 'features')
            <p class="mb-4 text-sm text-charcoal-600">
                Site feature switches. Turn these on when you are ready for them to appear on the public website.
            </p>
        @endif

        @forelse ($settings as $index => $setting)
            @php
                $raw = is_array($setting->value) ? ($setting->value['value'] ?? '') : $setting->value;
                $isBool = str_ends_with($setting->key, '_enabled') || in_array((string) $raw, ['0', '1', 'true', 'false'], true);
                $boolOn = filter_var($raw, FILTER_VALIDATE_BOOLEAN);
                $labels = [
                    'features.easy_read_enabled' => 'Show Easy Read floating button',
                ];
                $label = $labels[$setting->key] ?? $setting->key;
                $help = [
                    'features.easy_read_enabled' => 'When enabled, visitors see the Easy Read button on the home page and accessibility page.',
                ][$setting->key] ?? null;
            @endphp

            <input type="hidden" name="settings[{{ $index }}][key]" value="{{ $setting->key }}">
            <input type="hidden" name="settings[{{ $index }}][is_public]" value="1">

            @if ($isBool)
                <div class="{{ $index > 0 ? 'mt-6' : '' }} rounded-lg border border-charcoal-200 bg-sand/40 p-4">
                    <label class="flex items-start gap-3">
                        <input type="hidden" name="settings[{{ $index }}][value]" value="0">
                        <input
                            type="checkbox"
                            name="settings[{{ $index }}][value]"
                            value="1"
                            @checked(old('settings.'.$index.'.value', $boolOn))
                            class="mt-1 rounded border-charcoal-300 text-forest-600 focus:ring-forest-500"
                        >
                        <span>
                            <span class="block text-sm font-semibold text-charcoal-900">{{ $label }}</span>
                            @if($help)
                                <span class="mt-1 block text-sm text-charcoal-600">{{ $help }}</span>
                            @endif
                        </span>
                    </label>
                </div>
            @else
                <label for="setting_{{ $index }}" class="{{ $index > 0 ? 'mt-4' : '' }} block text-sm font-medium text-charcoal-700">{{ $label }}</label>
                <input
                    type="text"
                    name="settings[{{ $index }}][value]"
                    id="setting_{{ $index }}"
                    value="{{ old('settings.'.$index.'.value', $raw) }}"
                    class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500"
                >
            @endif
        @empty
            <p class="text-sm text-charcoal-500">No settings in this group yet.</p>
        @endforelse

        <button type="submit" class="mt-6 rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save settings</button>
    </form>
@endsection
