@props([
    'eyebrow' => 'A homegrown African model of inclusion',
    'headline' => '',
    'supportingText' => null,
    'primaryCta' => null,
    'secondaryCta' => null,
    'image' => null,
    'imageAlt' => null,
])

@php
    $hasNoChild = preg_match('/^(.*?)\b(No child)\b(.*)$/is', $headline, $m) === 1;
@endphp

<section class="relative overflow-hidden bg-ivory pb-20 pt-14 md:pb-24 md:pt-16" aria-labelledby="hero-heading">
    <div
        class="pointer-events-none absolute inset-0 bg-[radial-gradient(600px_300px_at_85%_10%,rgba(12,119,188,0.12),transparent_60%)]"
        aria-hidden="true"
    ></div>

    <div class="relative mx-auto grid max-w-editorial items-center gap-10 px-6 md:grid-cols-[1.1fr_0.9fr] md:gap-12 lg:px-7">
        <div class="reveal order-2 md:order-1">
            <span class="eyebrow">{{ $eyebrow }}</span>

            <h1 id="hero-heading" class="mt-3 font-display text-[clamp(2.4rem,5vw,4.1rem)] font-medium leading-[1.03] tracking-tight text-charcoal">
                @if($hasNoChild)
                    {!! nl2br(e($m[1])) !!}<em class="font-medium italic text-brand">{{ $m[2] }}</em>{!! nl2br(e($m[3])) !!}
                @else
                    {{ $headline }}
                @endif
            </h1>

            @if($supportingText)
                <p class="mt-5 max-w-[46ch] text-lg leading-relaxed text-charcoal-500">{{ $supportingText }}</p>
            @endif

            @if($primaryCta || $secondaryCta)
                <div class="mt-8 flex flex-wrap gap-3.5">
                    @if($primaryCta)
                        <a href="{{ $primaryCta['url'] ?? '#' }}" class="btn-primary">{{ $primaryCta['label'] ?? 'Learn more' }}</a>
                    @endif
                    @if($secondaryCta)
                        <a href="{{ $secondaryCta['url'] ?? '#' }}" class="btn-secondary">{{ $secondaryCta['label'] ?? 'Learn more' }}</a>
                    @endif
                </div>
            @endif
        </div>

        <div class="reveal order-1 w-full md:order-2">
            @if($image?->publicUrl())
                <x-public.media-frame
                    :asset="$image"
                    :alt="$imageAlt ?? ($image->alt ?? 'ASNEN community')"
                    ratio="1/1"
                    rounded="rounded-2xl"
                    class="w-full shadow-sm"
                />
            @else
                <div class="relative mx-auto w-full max-w-sm md:max-w-none" aria-hidden="true">
                    <x-public.media-frame
                        ratio="1/1"
                        rounded="rounded-2xl"
                        label="Hero photo placeholder"
                        class="w-full"
                    />
                    <div class="pointer-events-none absolute inset-0 flex items-center justify-center p-8 opacity-80">
                        <svg class="ring-art max-h-full w-full" viewBox="0 0 480 480" role="presentation">
                            <g class="ring-spin">
                                <circle cx="160" cy="160" r="60" stroke="#0C77BC"/>
                                <circle cx="270" cy="150" r="42" stroke="#8CC63F"/>
                                <circle cx="310" cy="250" r="58" stroke="#4A4C70"/>
                                <circle cx="205" cy="290" r="36" stroke="#75BDE7"/>
                                <circle cx="120" cy="255" r="26" stroke="#FFF200"/>
                                <circle cx="345" cy="120" r="20" stroke="#0C77BC"/>
                            </g>
                        </svg>
                    </div>
                    <p class="sr-only">Decorative artwork of interlocking rings representing community and Ubuntu. Replace with a hero photo from the admin Media Library.</p>
                </div>
            @endif
        </div>
    </div>
</section>
