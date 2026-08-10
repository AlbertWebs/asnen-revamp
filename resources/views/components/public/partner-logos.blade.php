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
    <ul class="partner-logo-grid" aria-label="Collaborator logos">
        @foreach($partners as $partner)
            @php $partner->loadMissing('logo'); @endphp
            <li class="partner-logo-grid__item">
                @if($partner->url)
                    <a href="{{ $partner->url }}" class="partner-logo-card" target="_blank" rel="noopener noreferrer">
                        <span class="partner-logo-card__media">
                            <x-public.media-frame
                                :asset="$partner->logo"
                                :alt="($partner->logo?->alt ?? $partner->name).' logo'"
                                ratio="1/1"
                                rounded="rounded-none"
                                fit="contain"
                                label="Logo"
                                class="partner-logo-card__frame"
                            />
                        </span>
                        <span class="partner-logo-card__name">{{ $partner->name }}</span>
                    </a>
                @else
                    <div class="partner-logo-card">
                        <span class="partner-logo-card__media">
                            <x-public.media-frame
                                :asset="$partner->logo"
                                :alt="($partner->logo?->alt ?? $partner->name).' logo'"
                                ratio="1/1"
                                rounded="rounded-none"
                                fit="contain"
                                label="Logo"
                                class="partner-logo-card__frame"
                            />
                        </span>
                        <span class="partner-logo-card__name">{{ $partner->name }}</span>
                    </div>
                @endif
            </li>
        @endforeach
    </ul>
@endif
