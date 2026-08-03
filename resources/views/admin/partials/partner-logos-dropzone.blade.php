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
    class="partner-logos-dropzone admin-form admin-form--wide mt-6"
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
                aria-label="Upload partner logos"
            >
            <div class="pointer-events-none">
                <p class="admin-file__title" x-text="uploading ? 'Uploading…' : 'Drop partner logos here or click to upload'"></p>
                <p class="admin-file__hint">PNG, JPG, WebP, or SVG · up to 5MB each · name is taken from the filename and can be edited below</p>
            </div>
        </div>

        <p x-show="error" x-cloak class="admin-error" x-text="error"></p>
        <p x-show="message" x-cloak class="admin-callout" x-text="message"></p>

        <div class="h-1.5 overflow-hidden rounded-full bg-sand" x-show="uploading" x-cloak>
            <div class="h-full bg-brand transition-all" :style="'width:' + progress + '%'"></div>
        </div>

        <div class="admin-callout" x-show="attachable.length">
            <label class="admin-label">Attach existing partner</label>
            <div class="mt-2 flex flex-wrap items-center gap-2">
                <select
                    class="admin-select min-w-[14rem] flex-1"
                    x-model="selectedPartnerId"
                >
                    <option value="">Choose a partner…</option>
                    <template x-for="partner in attachable" :key="partner.id">
                        <option :value="partner.id" x-text="partner.name"></option>
                    </template>
                </select>
                <button
                    type="button"
                    class="admin-btn-secondary"
                    @click="attachSelected()"
                >
                    Attach
                </button>
            </div>
        </div>

        <p x-show="items.length === 0 && !uploading" class="admin-callout text-center">
            No partners linked yet. Upload logos above or attach an existing partner.
        </p>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3" x-show="items.length">
            <template x-for="(item, index) in items" :key="item.id">
                <div class="overflow-hidden rounded-xl border border-charcoal/10 bg-white">
                    <div class="flex h-28 items-center justify-center bg-sand p-4">
                        <template x-if="item.url">
                            <img :src="item.url" :alt="item.alt || ''" class="max-h-full max-w-full object-contain">
                        </template>
                        <template x-if="!item.url">
                            <span class="text-xs text-charcoal/40">No logo</span>
                        </template>
                    </div>
                    <div class="space-y-2 p-3">
                        <label class="admin-label">Partner name</label>
                        <input
                            type="text"
                            class="admin-input"
                            x-model="item.name"
                            maxlength="255"
                            @change="saveName(item)"
                            @keydown.enter.prevent="saveName(item)"
                        >
                        <div class="flex items-center justify-between gap-2 pt-1">
                            <a :href="item.edit_url" class="text-xs font-medium text-brand hover:underline">Edit partner</a>
                            <button type="button" class="text-sm font-medium text-red-700 hover:underline" @click="removeItem(item, index)">Remove</button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
