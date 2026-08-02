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
    class="gallery-dropzone mt-6 max-w-4xl rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm"
    x-data="galleryDropzone({
        items: @js($initialItems),
        uploadUrl: @js(route('admin.galleries.upload', $gallery)),
        itemUpdateUrl: @js(route('admin.galleries.items.update', [$gallery, '__ID__'])),
        itemDeleteUrl: @js(route('admin.galleries.items.destroy', [$gallery, '__ID__'])),
        csrf: @js(csrf_token()),
    })"
>
    <div class="mb-4">
        <h2 class="text-base font-semibold text-charcoal-800">{{ $heading }}</h2>
        <p class="mt-1 text-sm text-charcoal-500">{{ $help }}</p>
    </div>

    <div
        class="relative rounded-lg border-2 border-dashed px-4 py-10 text-center transition"
        :class="dragging ? 'border-forest-500 bg-forest-50' : 'border-charcoal-300 bg-charcoal-50/60'"
        @dragenter.prevent="dragging = true"
        @dragover.prevent="dragging = true"
        @dragleave.prevent="dragging = false"
        @drop.prevent="dragging = false; handleFiles($event.dataTransfer.files)"
    >
        <input
            type="file"
            class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
            accept="image/*"
            multiple
            :disabled="uploading"
            @change="handleFiles($event.target.files); $event.target.value = ''"
            aria-label="Upload gallery images"
        >
        <div class="pointer-events-none space-y-2">
            <p class="text-sm font-medium text-charcoal-800" x-text="uploading ? 'Uploading…' : 'Drop images here or click to upload'"></p>
            <p class="text-xs text-charcoal-500">JPG, PNG, WebP, or GIF · up to 10MB each · multiple files allowed</p>
        </div>
    </div>

    <p x-show="error" x-cloak class="mt-3 rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-800" x-text="error"></p>
    <p x-show="message" x-cloak class="mt-3 rounded-md border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-800" x-text="message"></p>

    <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-charcoal-100" x-show="uploading" x-cloak>
        <div class="h-full bg-forest-600 transition-all" :style="'width:' + progress + '%'"></div>
    </div>

    <p x-show="items.length === 0 && !uploading" class="mt-4 rounded-md border border-dashed border-charcoal-200 bg-charcoal-50 px-4 py-6 text-center text-sm text-charcoal-500">
        No images yet. Drop photos above to build this gallery.
    </p>

    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3" x-show="items.length">
        <template x-for="(item, index) in items" :key="item.id">
            <div class="overflow-hidden rounded-lg border border-charcoal-200 bg-white">
                <div class="aspect-[4/3] bg-charcoal-100">
                    <img :src="item.url" :alt="item.alt || ''" class="h-full w-full object-cover">
                </div>
                <div class="space-y-2 p-3">
                    <label class="block text-xs font-medium uppercase tracking-wide text-charcoal-500">Caption</label>
                    <input
                        type="text"
                        class="block w-full rounded-md border-charcoal-300 text-sm shadow-sm focus:border-forest-500 focus:ring-forest-500"
                        x-model="item.caption"
                        maxlength="1000"
                        placeholder="Optional caption"
                        @change="saveCaption(item)"
                        @keydown.enter.prevent="saveCaption(item)"
                    >
                    <div class="flex items-center justify-between gap-2 pt-1">
                        <span class="font-mono text-[0.65rem] uppercase tracking-wide text-charcoal-400" x-text="'#' + (index + 1)"></span>
                        <button type="button" class="text-sm font-medium text-red-700 hover:underline" @click="removeItem(item, index)">Remove</button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
