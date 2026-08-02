@props([
    'partners' => collect(),
    'layout' => 'grid', // grid | marquee
])

@if($partners->isEmpty())
    <x-public.empty-state message="Partner logos will appear here soon." />
@elseif($layout === 'marquee')
    @php
        $track = $partners->values();
        $base = $track;
        while ($base->count() < 8) {
            $base = $base->concat($track);
        }
        $halfCount = $base->count();
        $loop = $base->concat($base);
    @endphp
    <div class="partner-marquee" data-partner-marquee>
        <div class="partner-marquee__fade partner-marquee__fade--left" aria-hidden="true"></div>
        <div class="partner-marquee__fade partner-marquee__fade--right" aria-hidden="true"></div>
        <div class="partner-marquee__viewport">
            <ul class="partner-marquee__track" aria-label="Partner logos">
                @foreach($loop as $index => $partner)
                    @php $partner->loadMissing('logo'); @endphp
                    <li class="partner-marquee__item" @if($index >= $halfCount) aria-hidden="true" @endif>
                        @if($partner->url && $index < $halfCount)
                            <a href="{{ $partner->url }}" class="partner-marquee__card" target="_blank" rel="noopener noreferrer">
                                <x-public.media-frame
                                    :asset="$partner->logo"
                                    :alt="($partner->logo?->alt ?? $partner->name).' logo'"
                                    ratio="3/2"
                                    rounded="rounded-lg"
                                    fit="contain"
                                    label="Logo"
                                    class="partner-marquee__logo bg-white"
                                />
                                <span class="sr-only">{{ $partner->name }}</span>
                            </a>
                        @else
                            <div class="partner-marquee__card">
                                <x-public.media-frame
                                    :asset="$partner->logo"
                                    :alt="($partner->logo?->alt ?? $partner->name).' logo'"
                                    ratio="3/2"
                                    rounded="rounded-lg"
                                    fit="contain"
                                    label="Logo"
                                    class="partner-marquee__logo bg-white"
                                />
                                @if($index < $halfCount)
                                    <span class="sr-only">{{ $partner->name }}</span>
                                @endif
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@else
    <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
        @foreach($partners as $partner)
            @php $partner->loadMissing('logo'); @endphp
            <div class="flex flex-col items-center gap-3 text-center">
                <x-public.media-frame
                    :asset="$partner->logo"
                    :alt="($partner->logo?->alt ?? $partner->name).' logo'"
                    ratio="1/1"
                    rounded="rounded-xl"
                    fit="contain"
                    label="Logo"
                    class="w-full max-w-[8rem] bg-white p-2"
                />
                <span class="text-sm font-semibold text-charcoal/80">{{ $partner->name }}</span>
            </div>
        @endforeach
    </div>
@endif
