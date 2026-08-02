@php
    $event->loadMissing('featuredImage');
    $typeLabel = match ($event->type) {
        'conference' => 'Conference',
        'workshop' => 'Workshop',
        'webinar' => 'Webinar',
        'outreach' => 'Outreach',
        default => $event->type ? \Illuminate\Support\Str::headline($event->type) : 'Event',
    };
    $meta = collect([
        $event->starts_at?->format('d M Y'),
        $event->is_online ? 'Online' : $event->venue,
    ])->filter()->implode(' · ');
@endphp

<article class="event-home-card group">
    <a href="{{ route('site.events.show', $event->slug) }}" class="event-home-card__media">
        <x-public.media-frame
            :asset="$event->featuredImage"
            :alt="$event->featuredImage?->alt ?? $event->title"
            ratio="16/9"
            rounded="rounded-none"
            label="Event photo"
            class="event-home-card__image"
        />
    </a>
    <div class="event-home-card__body">
        <div class="event-home-card__meta">
            <span class="event-home-card__type">{{ $typeLabel }}</span>
            @if($meta)
                <span class="event-home-card__date">{{ $meta }}</span>
            @endif
        </div>
        <h3 class="event-home-card__title">
            <a href="{{ route('site.events.show', $event->slug) }}">{{ $event->title }}</a>
        </h3>
        @if($event->summary)
            <p class="event-home-card__summary">{{ $event->summary }}</p>
        @endif
        <a href="{{ route('site.events.show', $event->slug) }}" class="event-home-card__link">
            {{ $event->isUpcoming() ? 'View details' : 'View recap' }}
            <span aria-hidden="true">→</span>
        </a>
    </div>
</article>
