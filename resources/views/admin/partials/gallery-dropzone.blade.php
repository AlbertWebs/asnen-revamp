@props([
    'gallery',
    'heading' => 'Gallery images',
    'help' => 'Drag and drop photos here, or click to browse. Captions appear on the public page.',
])

@php
    $gallery->loadMissing(['items.mediaAsset']);
    $initialItems = $gallery->items
        ->filter(fn ($item) => filled($item->mediaAsset?->publicUrl()))
        ->map(fn ($item) => [
            'id' => $item->id,
            'media_asset_id' => $item->media_asset_id,
            'caption' => $item->caption ?? '',
            'sort_order' => $item->sort_order,
            'url' => $item->mediaAsset->publicUrl(),
            'alt' => $item->mediaAsset->alt ?: $item->mediaAsset->filename,
        ])
        ->values()
        ->all();
@endphp

<div
    class="gallery-dropzone admin-form admin-form--wide mt-6"
    x-data="galleryDropzone({
        items: @js($initialItems),
        uploadUrl: @js(route('admin.galleries.upload', $gallery)),
        itemUpdateUrl: @js(route('admin.galleries.items.update', [$gallery, '__ID__'])),
        itemDeleteUrl: @js(route('admin.galleries.items.destroy', [$gallery, '__ID__'])),
        csrf: @js(csrf_token()),
    })"
>
    <div class="admin-form__body">
        <div class="admin-form__header">
            <h2 class="admin-form__title">{{ $heading }}</h2>
            <p class="admin-form__intro">{{ $help }}</p>
        </div>

        <div
            class="admin-file"
            :class="dragging ? 'border-brand ring-2 ring-brand/25' : ''"
            @dragenter.prevent="dragging = true"
            @dragover.prevent="dragging = true"
            @dragleave.prevent="dragging = false"
            @drop.prevent="dragging = false; handleFiles($event.dataTransfer.files)"
        >
            <input
                type="file"
                accept="image/*"
                multiple
                :disabled="uploading"
                @change="handleFiles($event.target.files); $event.target.value = ''"
                aria-label="Upload gallery images"
            >
            <div class="pointer-events-none">
                <p class="admin-file__title" x-text="uploading ? 'Uploading…' : 'Drop images here or click to upload'"></p>
                <p class="admin-file__hint">JPG, PNG, WebP, or GIF · up to 10MB each · multiple files allowed</p>
            </div>
        </div>

        <p x-show="error" x-cloak class="admin-error" x-text="error"></p>
        <p x-show="message" x-cloak class="admin-callout" x-text="message"></p>

        <div class="h-1.5 overflow-hidden rounded-full bg-sand" x-show="uploading" x-cloak>
            <div class="h-full bg-brand transition-all" :style="'width:' + progress + '%'"></div>
        </div>

        <p x-show="items.length === 0 && !uploading" class="admin-callout text-center">
            No images yet. Drop photos above to build this gallery.
        </p>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3" x-show="items.length">
            <template x-for="(item, index) in items" :key="item.id">
                <div class="overflow-hidden rounded-xl border border-charcoal/10 bg-white">
                    <div class="aspect-[4/3] bg-sand">
                        <img :src="item.url" :alt="item.alt || ''" class="h-full w-full object-cover">
                    </div>
                    <div class="space-y-2 p-3">
                        <label class="admin-label">Caption</label>
                        <input
                            type="text"
                            class="admin-input"
                            x-model="item.caption"
                            maxlength="1000"
                            placeholder="Optional caption"
                            @change="saveCaption(item)"
                            @keydown.enter.prevent="saveCaption(item)"
                        >
                        <div class="flex items-center justify-between gap-2 pt-1">
                            <span class="font-mono text-[0.65rem] uppercase tracking-wide text-charcoal/40" x-text="'#' + (index + 1)"></span>
                            <button type="button" class="text-sm font-medium text-red-700 hover:underline" @click="removeItem(item, index)">Remove</button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
