@php
    $c = $content;
    $image = ! empty($c['image_id'])
        ? \App\Models\MediaAsset::query()->find($c['image_id'])
        : null;
    $imageUrl = $image?->publicUrl();
    $imageAlt = $c['image_alt'] ?? ($image?->alt ?? 'About ASNEN');
@endphp
<x-public.section tone="ivory">
    <div class="grid gap-10 lg:grid-cols-2 lg:items-stretch">
        <div class="flex flex-col">
            <div class="section-head">
                <span class="eyebrow mb-3 block">About ASNEN</span>
                <h2>{{ $c['heading'] ?? 'Who We Are' }}</h2>
            </div>
            <div class="max-w-[75ch] text-lg leading-relaxed text-charcoal-500">
                <x-public.prose :html="$sanitizer->clean($c['body'] ?? '')" />
            </div>
        </div>

        <div class="relative min-h-[16rem] overflow-hidden rounded-xl bg-sand lg:min-h-0">
            @if($imageUrl)
                <img
                    src="{{ $imageUrl }}"
                    alt="{{ $imageAlt }}"
                    class="absolute inset-0 h-full w-full object-cover"
                    loading="lazy"
                    decoding="async"
                >
            @else
                <div
                    class="absolute inset-0 flex flex-col items-center justify-center gap-2 border border-dashed border-brand/30 bg-gradient-to-br from-brand-50 via-sand to-lime-50 text-center"
                    role="img"
                    aria-label="About image placeholder"
                >
                    <svg class="h-10 w-10 text-brand/40" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                        <circle cx="16" cy="16" r="8" stroke="currentColor" stroke-width="2"/>
                        <circle cx="30" cy="14" r="6" stroke="currentColor" stroke-width="2"/>
                        <circle cx="34" cy="28" r="8" stroke="currentColor" stroke-width="2"/>
                        <circle cx="18" cy="32" r="5" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    <span class="px-3 font-mono text-[0.65rem] uppercase tracking-wider text-brand/70">About image placeholder</span>
                </div>
            @endif
        </div>
    </div>
</x-public.section>
