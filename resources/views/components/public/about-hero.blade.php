@props([
    'breadcrumb' => 'About',
    'breadcrumbUrl' => null,
    'currentLabel' => null,
    'eyebrow' => null,
    'brand' => 'ASNEN',
    'title',
    'titleMax' => '14ch',
    'tagline' => null,
    'excerpt' => null,
    'primaryCta' => null,
    'secondaryCta' => null,
    'showVisual' => false,
])

<section class="impact-hero who-hero">
    <div class="mx-auto max-w-editorial px-6 lg:px-7">
        <div class="{{ $showVisual ? 'who-hero__grid' : 'impact-hero__inner' }} reveal">
            <div class="who-hero__copy">
                <x-public.breadcrumbs :items="array_values(array_filter([
                    ['label' => $breadcrumb, 'url' => $breadcrumbUrl ?? route('site.about.who-we-are')],
                    $currentLabel ? ['label' => $currentLabel] : null,
                ]))" />

                @if($brand)
                    <p class="who-hero__brand">{{ $brand }}</p>
                @elseif($eyebrow)
                    <span class="eyebrow mt-6 block">{{ $eyebrow }}</span>
                @endif

                <h1 class="impact-hero__title" style="max-width: {{ $titleMax }};">{{ $title }}</h1>

                @if($tagline)
                    <p class="who-hero__tagline">{{ $tagline }}</p>
                @endif

                @if($excerpt)
                    <p class="impact-hero__excerpt">{{ $excerpt }}</p>
                @endif

                @if($slot->isNotEmpty())
                    <div class="impact-hero__body">
                        {{ $slot }}
                    </div>
                @endif

                @if($primaryCta || $secondaryCta)
                    <div class="impact-hero__actions">
                        @if($primaryCta)
                            <a href="{{ $primaryCta['url'] }}" class="btn-primary">{{ $primaryCta['label'] }}</a>
                        @endif
                        @if($secondaryCta)
                            <a href="{{ $secondaryCta['url'] }}" class="btn-secondary">{{ $secondaryCta['label'] }}</a>
                        @endif
                    </div>
                @endif
            </div>

            @if($showVisual)
                <div class="who-hero__visual" aria-hidden="true">
                    <svg class="ring-art who-hero__rings" viewBox="0 0 480 480" role="presentation">
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
            @endif
        </div>
    </div>
</section>
