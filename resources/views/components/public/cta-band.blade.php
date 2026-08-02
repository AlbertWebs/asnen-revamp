@props(['heading' => null, 'text' => null, 'primaryCta' => null, 'secondaryCta' => null])

<section class="bg-brand py-16 text-white md:py-20">
    <div class="mx-auto max-w-editorial px-6 text-center lg:px-7">
        @if($heading)
            <h2 class="font-display text-3xl font-medium md:text-4xl">{{ $heading }}</h2>
        @endif
        @if($text)
            <p class="mx-auto mt-4 max-w-2xl text-brand-100">{{ $text }}</p>
        @endif
        @if($primaryCta || $secondaryCta || $slot->isNotEmpty())
            <div class="mt-8 flex flex-wrap justify-center gap-3.5">
                {{ $slot }}
                @if($primaryCta)
                    <a href="{{ $primaryCta['url'] ?? '#' }}" class="btn-gold">{{ $primaryCta['label'] ?? 'Get started' }}</a>
                @endif
                @if($secondaryCta)
                    <a href="{{ $secondaryCta['url'] ?? '#' }}" class="btn-secondary border-white/50 text-white hover:border-gold hover:text-gold">{{ $secondaryCta['label'] ?? 'Learn more' }}</a>
                @endif
            </div>
        @endif
    </div>
</section>
