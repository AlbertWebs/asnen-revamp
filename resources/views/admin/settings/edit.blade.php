@extends('layouts.admin')

@section('title', 'Settings: '.ucfirst($group))
@section('heading', 'Settings: '.ucfirst($group))

@section('content')
    <div class="mb-5 flex flex-wrap gap-2">
        @foreach (['brand', 'contact', 'social', 'features', 'seo', 'website'] as $tab)
            <a
                href="{{ route('admin.settings.edit', $tab) }}"
                class="{{ $group === $tab ? 'admin-btn-primary' : 'admin-btn-secondary' }}"
            >
                {{ ucfirst($tab) }}
            </a>
        @endforeach
    </div>

    <form method="POST" action="{{ route('admin.settings.update', $group) }}" class="admin-form admin-form--wide">
        @csrf
        @method('PUT')

        <div class="admin-form__body">
            <div class="admin-form__section">
                @if ($group === 'features')
                    <p class="admin-hint">
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
                            'contact.city' => 'City / location',
                            'contact.email' => 'Primary email',
                            'contact.phone_primary' => 'Primary phone',
                            'contact.phone_secondary' => 'Secondary phone',
                        ];
                        $label = $labels[$setting->key] ?? $setting->key;
                        $help = [
                            'features.easy_read_enabled' => 'When enabled, visitors see the Easy Read button on the home page and accessibility page.',
                            'contact.email' => 'Shown on the public contact page and used for inquiries.',
                            'contact.phone_primary' => 'Shown as the primary call line on the contact page.',
                            'contact.phone_secondary' => 'Optional second phone line on the contact page.',
                        ][$setting->key] ?? null;
                    @endphp

                    <input type="hidden" name="settings[{{ $index }}][key]" value="{{ $setting->key }}">
                    <input type="hidden" name="settings[{{ $index }}][is_public]" value="1">

                    @if ($isBool)
                        <div class="admin-callout">
                            <label class="admin-check">
                                <input type="hidden" name="settings[{{ $index }}][value]" value="0">
                                <input
                                    type="checkbox"
                                    name="settings[{{ $index }}][value]"
                                    value="1"
                                    @checked(old('settings.'.$index.'.value', $boolOn))
                                >
                                <span>
                                    <span class="block font-semibold text-charcoal">{{ $label }}</span>
                                    @if($help)
                                        <span class="admin-hint mt-1 block">{{ $help }}</span>
                                    @endif
                                </span>
                            </label>
                        </div>
                    @else
                        <div class="admin-field">
                            <label for="setting_{{ $index }}" class="admin-label">{{ $label }}</label>
                            @if($help)
                                <p class="admin-hint">{{ $help }}</p>
                            @endif
                            <input
                                type="text"
                                name="settings[{{ $index }}][value]"
                                id="setting_{{ $index }}"
                                value="{{ old('settings.'.$index.'.value', $raw) }}"
                                class="admin-input"
                            >
                        </div>
                    @endif
                @empty
                    <p class="admin-hint">No settings in this group yet.</p>
                @endforelse
            </div>

            @if ($settings->isNotEmpty())
                <div class="admin-form__actions">
                    <button type="submit" class="admin-btn-primary">Save settings</button>
                </div>
            @endif
        </div>
    </form>
@endsection
