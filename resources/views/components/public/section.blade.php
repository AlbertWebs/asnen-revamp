@props(['heading' => null, 'eyebrow' => null, 'tone' => 'ivory'])

@php
    $bg = match ($tone) {
        'soft', 'bone' => 'bg-sand',
        'indigo', 'charcoal' => 'bg-charcoal text-white',
        default => 'bg-ivory',
    };
@endphp

<section {{ $attributes->merge(['class' => "section-editorial {$bg}"]) }}>
    <div class="mx-auto max-w-editorial px-6 lg:px-7">
        @if($heading || $eyebrow)
            <div class="section-head reveal">
                @if($eyebrow)
                    <span class="eyebrow mb-3 block">{{ $eyebrow }}</span>
                @endif
                @if($heading)
                    <h2>{{ $heading }}</h2>
                @endif
            </div>
        @endif
        <div class="reveal">
            {{ $slot }}
        </div>
    </div>
</section>
