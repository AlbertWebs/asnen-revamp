@extends('layouts.admin')

@section('title', 'Edit Media')
@section('heading', 'Edit Media')

@section('content')
    <div class="mb-5">
        <a href="{{ route('admin.media.index') }}" class="admin-btn-ghost !min-h-0 !px-0 !py-0 text-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to media library
        </a>
    </div>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_16rem]">
        <form method="POST" action="{{ route('admin.media.update', $asset) }}" class="admin-form">
            @csrf
            @method('PUT')

            <div class="admin-form__body">
                <header class="admin-form__header">
                    <p class="admin-form__eyebrow">Media library</p>
                    <h2 class="admin-form__title">Edit asset details</h2>
                    <p class="admin-form__intro">
                        Update accessibility text, consent, and credit for
                        <span class="font-semibold text-charcoal">{{ $asset->filename }}</span>
                        ({{ number_format($asset->size / 1024, 1) }} KB).
                    </p>
                </header>

                <div class="admin-form__section">
                    <p class="admin-form__section-title">Details</p>

                    <div class="admin-field">
                        <label for="alt" class="admin-label">Alt text</label>
                        <p class="admin-hint">Describe the image for screen readers and when the file can’t load.</p>
                        <input
                            type="text"
                            name="alt"
                            id="alt"
                            value="{{ old('alt', $asset->alt) }}"
                            class="admin-input"
                            @error('alt') aria-invalid="true" @enderror
                        >
                        @error('alt')
                            <p class="admin-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="admin-field">
                        <label for="caption" class="admin-label">Caption</label>
                        <textarea
                            name="caption"
                            id="caption"
                            rows="3"
                            class="admin-textarea"
                            @error('caption') aria-invalid="true" @enderror
                        >{{ old('caption', $asset->caption) }}</textarea>
                        @error('caption')
                            <p class="admin-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="admin-field">
                        <label for="credit" class="admin-label">Credit</label>
                        <input
                            type="text"
                            name="credit"
                            id="credit"
                            value="{{ old('credit', $asset->credit) }}"
                            class="admin-input"
                            placeholder="Photographer or source"
                        >
                    </div>
                </div>

                <div class="admin-form__section">
                    <p class="admin-form__section-title">Consent & privacy</p>

                    <div class="admin-field">
                        <label for="consent_status" class="admin-label">Consent status</label>
                        <select name="consent_status" id="consent_status" class="admin-select">
                            @foreach (\App\Enums\ConsentStatus::cases() as $status)
                                <option value="{{ $status->value }}" @selected(old('consent_status', $asset->consent_status?->value ?? $asset->consent_status) === $status->value)>
                                    {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="admin-field">
                        <label for="consent_notes" class="admin-label">Consent notes</label>
                        <textarea
                            name="consent_notes"
                            id="consent_notes"
                            rows="2"
                            class="admin-textarea"
                            placeholder="Where consent was recorded, restrictions, expiry…"
                        >{{ old('consent_notes', $asset->consent_notes) }}</textarea>
                    </div>

                    <label class="admin-check">
                        <input type="checkbox" name="is_private" value="1" @checked(old('is_private', $asset->is_private))>
                        <span>
                            <span class="block font-semibold text-charcoal">Private asset</span>
                            <span class="block text-xs text-charcoal/50">Hide from public media URLs where supported.</span>
                        </span>
                    </label>
                </div>

                <div class="admin-form__actions">
                    <button type="submit" class="admin-btn-primary">Save changes</button>
                    <a href="{{ route('admin.media.index') }}" class="admin-btn-secondary">Cancel</a>
                </div>
            </div>
        </form>

        <aside class="space-y-4">
            <div class="overflow-hidden rounded-2xl border border-charcoal/10 bg-white shadow-sm">
                <div class="aspect-video bg-sand flex items-center justify-center text-xs text-charcoal/50">
                    @if ($asset->isImage())
                        <img src="{{ asset('storage/'.$asset->path) }}" alt="{{ e($asset->alt ?? '') }}" class="h-full w-full object-cover">
                    @else
                        <x-admin.file-format-icon :kind="$asset->formatKind()" :label="$asset->formatLabel()" />
                    @endif
                </div>
                <div class="space-y-1 p-4 text-sm">
                    <p class="truncate font-semibold text-charcoal" title="{{ $asset->filename }}">{{ $asset->filename }}</p>
                    <p class="text-xs text-charcoal/50">{{ $asset->mime }} · {{ number_format($asset->size / 1024, 1) }} KB</p>
                    <p class="text-xs text-charcoal/50">Folder: {{ $asset->folder ?? '—' }}</p>
                </div>
            </div>

            @can('media.delete')
                <form method="POST" action="{{ route('admin.media.destroy', $asset) }}" onsubmit="return confirm('Delete this media asset?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-btn-danger w-full">Delete asset</button>
                </form>
            @endcan
        </aside>
    </div>
@endsection
