@props([
    'story',
    'availablePartners' => collect(),
    'heading' => 'Partners on the day',
    'help' => 'Upload partner logos for this story. They appear in the Partners on the day section on the public page.',
])

@php
    $story->loadMissing(['partners.logo']);
    $initialItems = $story->partners
        ->map(fn ($partner) => [
            'id' => $partner->id,
            'name' => $partner->name,
            'url' => $partner->logo?->publicUrl(),
            'alt' => $partner->logo?->alt ?: ($partner->name.' logo'),
            'edit_url' => route('admin.partners.edit', $partner),
        ])
        ->values()
        ->all();

    $available = collect($availablePartners)
        ->map(fn ($partner) => [
            'id' => $partner->id,
            'name' => $partner->name,
            'url' => $partner->logo?->publicUrl(),
            'alt' => $partner->logo?->alt ?: ($partner->name.' logo'),
            'edit_url' => route('admin.partners.edit', $partner),
        ])
        ->values()
        ->all();
@endphp

<div
    class="partner-logos-dropzone mt-6 max-w-4xl rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm"
    x-data="partnerLogosDropzone({
        items: @js($initialItems),
        available: @js($available),
        uploadUrl: @js(route('admin.impact-stories.partners.upload', $story)),
        attachUrl: @js(route('admin.impact-stories.partners.attach', $story)),
        itemUpdateUrl: @js(route('admin.impact-stories.partners.update', [$story, '__ID__'])),
        itemDetachUrl: @js(route('admin.impact-stories.partners.detach', [$story, '__ID__'])),
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
            aria-label="Upload partner logos"
        >
        <div class="pointer-events-none space-y-2">
            <p class="text-sm font-medium text-charcoal-800" x-text="uploading ? 'Uploading…' : 'Drop partner logos here or click to upload'"></p>
            <p class="text-xs text-charcoal-500">PNG, JPG, WebP, or SVG · up to 5MB each · name is taken from the filename and can be edited below</p>
        </div>
    </div>

    <p x-show="error" x-cloak class="mt-3 rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-800" x-text="error"></p>
    <p x-show="message" x-cloak class="mt-3 rounded-md border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-800" x-text="message"></p>

    <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-charcoal-100" x-show="uploading" x-cloak>
        <div class="h-full bg-forest-600 transition-all" :style="'width:' + progress + '%'"></div>
    </div>

    <div class="mt-4 rounded-md border border-charcoal-200 bg-charcoal-50/50 p-3" x-show="attachable.length">
        <label class="block text-xs font-medium uppercase tracking-wide text-charcoal-500">Attach existing partner</label>
        <div class="mt-2 flex flex-wrap items-center gap-2">
            <select
                class="min-w-[14rem] flex-1 rounded-md border-charcoal-300 text-sm shadow-sm focus:border-forest-500 focus:ring-forest-500"
                x-model="selectedPartnerId"
            >
                <option value="">Choose a partner…</option>
                <template x-for="partner in attachable" :key="partner.id">
                    <option :value="partner.id" x-text="partner.name"></option>
                </template>
            </select>
            <button
                type="button"
                class="rounded-md border border-charcoal-300 bg-white px-3 py-2 text-sm font-medium text-charcoal-800 hover:bg-charcoal-50"
                @click="attachSelected()"
            >
                Attach
            </button>
        </div>
    </div>

    <p x-show="items.length === 0 && !uploading" class="mt-4 rounded-md border border-dashed border-charcoal-200 bg-charcoal-50 px-4 py-6 text-center text-sm text-charcoal-500">
        No partners linked yet. Upload logos above or attach an existing partner.
    </p>

    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3" x-show="items.length">
        <template x-for="(item, index) in items" :key="item.id">
            <div class="overflow-hidden rounded-lg border border-charcoal-200 bg-white">
                <div class="flex h-28 items-center justify-center bg-charcoal-50 p-4">
                    <template x-if="item.url">
                        <img :src="item.url" :alt="item.alt || ''" class="max-h-full max-w-full object-contain">
                    </template>
                    <template x-if="!item.url">
                        <span class="text-xs text-charcoal-400">No logo</span>
                    </template>
                </div>
                <div class="space-y-2 p-3">
                    <label class="block text-xs font-medium uppercase tracking-wide text-charcoal-500">Partner name</label>
                    <input
                        type="text"
                        class="block w-full rounded-md border-charcoal-300 text-sm shadow-sm focus:border-forest-500 focus:ring-forest-500"
                        x-model="item.name"
                        maxlength="255"
                        @change="saveName(item)"
                        @keydown.enter.prevent="saveName(item)"
                    >
                    <div class="flex items-center justify-between gap-2 pt-1">
                        <a :href="item.edit_url" class="text-xs font-medium text-forest-700 hover:underline">Edit partner</a>
                        <button type="button" class="text-sm font-medium text-red-700 hover:underline" @click="removeItem(item, index)">Remove</button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
