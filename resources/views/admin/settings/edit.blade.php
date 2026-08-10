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

    <form method="POST" action="{{ route('admin.settings.update', $group) }}" class="admin-form admin-form--wide" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="admin-form__body">
            <div class="admin-form__section">
                @if ($group === 'brand')
                    <p class="admin-hint mb-4">
                        Update the organisation name shown across the site, and replace the header logo used on the public website and admin.
                    </p>

                    <div class="admin-field mb-6">
                        <span class="admin-label">Current logo preview</span>
                        <div class="mt-2 inline-flex items-center justify-center rounded-xl border border-charcoal/10 bg-white p-4">
                            <img
                                src="{{ $logoPreviewUrl ?? asset('brand/logo.png') }}"
                                alt="Current site logo"
                                class="h-16 w-auto max-w-[14rem] object-contain"
                            >
                        </div>
                    </div>

                    @include('admin.partials.media-picker', [
                        'name' => 'logo_id',
                        'value' => $logoId ?? null,
                        'label' => 'Site logo',
                        'folder' => 'brand',
                        'help' => 'Upload a PNG or SVG with a transparent background when possible. This replaces the logo in the public header, admin sidebar, and favicon.',
                    ])
                @endif

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
                            'brand.name' => 'Organisation name',
                            'brand.short_name' => 'Short name',
                            'brand.tagline' => 'Tagline',
                            'features.easy_read_enabled' => 'Show Easy Read floating button',
                            'contact.city' => 'City / location',
                            'contact.email' => 'Primary email',
                            'contact.phone_primary' => 'Primary phone',
                            'contact.phone_secondary' => 'Secondary phone',
                        ];
                        $label = $labels[$setting->key] ?? $setting->key;
                        $help = [
                            'brand.name' => 'Full legal / public organisation name.',
                            'brand.short_name' => 'Short label used in compact UI spaces.',
                            'brand.tagline' => 'Shown in SEO and brand moments across the site.',
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
                    @if ($group !== 'brand')
                        <p class="admin-hint">No settings in this group yet.</p>
                    @endif
                @endforelse
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn-primary">Save settings</button>
            </div>
        </div>
    </form>
@endsection
