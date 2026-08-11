@extends('layouts.admin')

@section('title', 'Hero images')
@section('heading', 'Hero images')

@section('content')
    @php
        $previewMap = $images->mapWithKeys(fn ($image) => [
            $image->id => [
                'url' => $image->publicUrl(),
                'label' => ($image->alt ?: $image->filename).' (#'.$image->id.')',
            ],
        ])->all();

        foreach ($selectedImages as $image) {
            $previewMap[$image->id] = [
                'url' => $image->publicUrl(),
                'label' => ($image->alt ?: $image->filename).' (#'.$image->id.')',
            ];
        }
    @endphp

    <div class="admin-toolbar">
        <p class="admin-toolbar__copy">
            Choose the rotating photos for the home page hero. Select two or more for a carousel. Order below controls slide sequence.
        </p>
        <div class="admin-toolbar__actions">
            @can('media.upload')
                <a
                    href="{{ route('admin.media.create', ['folder' => 'hero', 'return' => route('admin.hero-images.edit')]) }}"
                    class="admin-btn-secondary"
                >
                    Upload images
                </a>
            @endcan
            <a href="{{ url('/') }}" target="_blank" rel="noopener" class="admin-btn-ghost">
                View homepage
            </a>
        </div>
    </div>

    <form
        method="POST"
        action="{{ route('admin.hero-images.update') }}"
        class="admin-form admin-form--wide"
        x-data="heroImagePicker(@js($selectedIds), @js($previewMap))"
    >
        @csrf
        @method('PUT')

        <template x-for="id in selectedIds" :key="'hidden-' + id">
            <input type="hidden" name="image_ids[]" :value="id">
        </template>

        <div class="admin-form__body space-y-6">
            <div class="admin-form__section">
                <div class="mb-3 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <p class="admin-form__section-title !mb-1">Selected slides</p>
                        <p class="admin-hint" x-text="selectedIds.length + ' image' + (selectedIds.length === 1 ? '' : 's') + ' in the hero carousel'"></p>
                    </div>
                    <button
                        type="button"
                        class="admin-btn-ghost !min-h-0 !px-2 !py-1 text-sm"
                        x-show="selectedIds.length"
                        x-cloak
                        @click="selectedIds = []"
                    >
                        Clear all
                    </button>
                </div>

                <div class="admin-hero-selected" x-show="selectedIds.length" x-cloak>
                    <template x-for="(id, index) in selectedIds" :key="'sel-' + id">
                        <div class="admin-hero-selected__item">
                            <template x-if="previewFor(id)">
                                <img :src="previewFor(id)" alt="" class="admin-hero-selected__img">
                            </template>
                            <template x-if="!previewFor(id)">
                                <span class="admin-hero-selected__fallback">#<span x-text="id"></span></span>
                            </template>
                            <div class="admin-hero-selected__meta">
                                <span class="admin-hero-selected__order" x-text="'Slide ' + (index + 1)"></span>
                                <span class="admin-hero-selected__label" x-text="labelFor(id)"></span>
                            </div>
                            <div class="admin-hero-selected__actions">
                                <button type="button" class="admin-hero-selected__btn" @click="move(index, -1)" :disabled="index === 0" aria-label="Move earlier">↑</button>
                                <button type="button" class="admin-hero-selected__btn" @click="move(index, 1)" :disabled="index === selectedIds.length - 1" aria-label="Move later">↓</button>
                                <button type="button" class="admin-hero-selected__btn admin-hero-selected__btn--remove" @click="toggle(id)" aria-label="Remove slide">×</button>
                            </div>
                        </div>
                    </template>
                </div>

                <p class="rounded-xl border border-dashed border-charcoal/15 bg-sand/60 px-4 py-8 text-center text-sm text-charcoal/55" x-show="!selectedIds.length">
                    No hero images selected yet. Pick photos from the library below.
                </p>
            </div>

            <div class="admin-form__section">
                <p class="admin-form__section-title">Media library</p>
                <p class="admin-hint mb-3">Click a thumbnail to add or remove it from the hero. Newest uploads appear first.</p>

                @if ($images->isEmpty())
                    <p class="py-6 text-center text-sm text-charcoal/50">
                        No images in the library yet.
                        @can('media.upload')
                            <a href="{{ route('admin.media.create', ['folder' => 'hero', 'return' => route('admin.hero-images.edit')]) }}" class="admin-table__link">Upload images</a>
                        @endcan
                    </p>
                @else
                    <div class="admin-media-grid max-h-[36rem] overflow-y-auto rounded-xl border border-charcoal/10 bg-white p-3">
                        @foreach ($images as $image)
                            <button
                                type="button"
                                class="admin-media-thumb"
                                :class="{ 'is-selected': isSelected({{ $image->id }}) }"
                                @click="toggle({{ $image->id }})"
                                :aria-pressed="isSelected({{ $image->id }}) ? 'true' : 'false'"
                            >
                                @if ($image->publicUrl())
                                    <img src="{{ $image->publicUrl() }}" alt="{{ e($image->alt ?? $image->filename) }}" loading="lazy" class="admin-media-thumb__img">
                                @else
                                    <span class="admin-media-thumb__fallback" aria-hidden="true">No preview</span>
                                @endif
                                <span class="admin-media-thumb__caption">{{ $image->alt ?: $image->filename }}</span>
                                <span class="admin-media-thumb__check" aria-hidden="true">
                                    <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                </span>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="admin-form__actions mt-6">
            <button type="submit" class="admin-btn-primary">Save hero images</button>
            <a href="{{ route('admin.media.index') }}" class="admin-btn-secondary">Back to media library</a>
        </div>
    </form>

    @push('scripts')
        <script>
            function heroImagePicker(initialIds, previewMap) {
                return {
                    selectedIds: (initialIds || []).map(Number),
                    previews: previewMap || {},
                    isSelected(id) {
                        return this.selectedIds.includes(Number(id));
                    },
                    toggle(id) {
                        id = Number(id);
                        const idx = this.selectedIds.indexOf(id);
                        if (idx === -1) this.selectedIds.push(id);
                        else this.selectedIds.splice(idx, 1);
                    },
                    move(index, delta) {
                        const next = index + delta;
                        if (next < 0 || next >= this.selectedIds.length) return;
                        const copy = this.selectedIds.slice();
                        const tmp = copy[index];
                        copy[index] = copy[next];
                        copy[next] = tmp;
                        this.selectedIds = copy;
                    },
                    previewFor(id) {
                        return (this.previews[Number(id)] && this.previews[Number(id)].url) || '';
                    },
                    labelFor(id) {
                        return (this.previews[Number(id)] && this.previews[Number(id)].label) || ('#' + id);
                    },
                };
            }
        </script>
    @endpush
@endsection
