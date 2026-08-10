@php
    $c = $content;
    $limit = max(1, (int) ($c['limit'] ?? 3));
    $upcomingOnly = (bool) ($c['show_upcoming_only'] ?? true);
    $pastOnly = (bool) ($c['show_past_only'] ?? false);
    $fallbackToPast = (bool) ($c['fallback_to_past'] ?? true);

    $upcomingQuery = fn () => \App\Models\Event::published()
        ->with('featuredImage')
        ->where('starts_at', '>=', now())
        ->orderBy('starts_at');

    $pastQuery = fn () => \App\Models\Event::published()
        ->with('featuredImage')
        ->where('starts_at', '<', now())
        ->orderByDesc('starts_at');

    $mode = 'upcoming';
    if ($pastOnly) {
        $events = $pastQuery()->limit($limit)->get();
        $mode = 'past';
    } else {
        $events = $upcomingQuery()->limit($limit)->get();
        $mode = 'upcoming';

        if ($events->count() < $limit && ($fallbackToPast || ! $upcomingOnly)) {
            $needed = $limit - $events->count();
            $past = $pastQuery()->limit($needed)->get();
            if ($past->isNotEmpty()) {
                $events = $events->concat($past)->values();
                $mode = $events->first(fn ($e) => $e->starts_at && $e->starts_at->gte(now()))
                    ? 'mixed'
                    : 'past';
            }
        }
    }

    $browseUrl = match ($mode) {
        'past' => route('site.events.past'),
        'mixed' => route('site.events.index'),
        default => route('site.events.upcoming'),
    };
    $browseLabel = match ($mode) {
        'past' => 'View all past events',
        'mixed' => 'Browse all events',
        default => 'All upcoming events',
    };
    // Prefer upcoming copy, but switch labels when we had to fall back to past events.
    $heading = match ($mode) {
        'past' => 'Recent events',
        'mixed' => $c['heading'] ?? 'Events & learning',
        default => $c['heading'] ?? 'Upcoming events',
    };
    $intro = match ($mode) {
        'past' => 'No upcoming dates are published yet, so here are recent gatherings from across the ASNEN network.',
        'mixed' => $c['intro'] ?? 'What is coming next, plus recent gatherings from across the ASNEN network.',
        default => $c['intro'] ?? 'Conferences, webinars, and gatherings coming up across the ASNEN network.',
    };
@endphp

<section class="section-editorial bg-sand">
    <div class="mx-auto max-w-editorial px-6 lg:px-7">
        <div class="section-head-row reveal">
            <div class="section-head">
                <span class="eyebrow mb-3 block">Events &amp; Learning</span>
                <h2>{{ $heading }}</h2>
                <p class="section-head-row__intro">{{ $intro }}</p>
            </div>
            <a href="{{ $browseUrl }}" class="btn-secondary section-head-row__cta">{{ $browseLabel }}</a>
        </div>

        <div class="reveal">
            @if($events->isNotEmpty())
                <div class="events-home-grid">
                    @foreach($events as $event)
                        @php
                            $typeLabel = match ($event->type) {
                                'conference' => 'Conference',
                                'webinar' => 'Webinar',
                                default => 'Event',
                            };
                            $isUpcoming = $event->starts_at && $event->starts_at->gte(now());
                            $meta = collect([
                                $event->starts_at?->format('d M Y'),
                                $event->is_online ? 'Online' : ($event->venue ?: null),
                            ])->filter()->implode(' · ');
                        @endphp
                        <article class="event-home-card group">
                            <a href="{{ route('site.events.show', $event->slug) }}" class="event-home-card__media">
                                <x-public.media-frame
                                    :asset="$event->featuredImage"
                                    :alt="$event->featuredImage?->alt ?? $event->title"
                                    ratio="16/9"
                                    rounded="rounded-none"
                                    label="Event image"
                                    class="event-home-card__image"
                                />
                            </a>
                            <div class="event-home-card__body">
                                <div class="event-home-card__meta">
                                    <span class="event-home-card__type">{{ $typeLabel }}</span>
                                    @if($isUpcoming)
                                        <span class="event-home-card__status">Upcoming</span>
                                    @endif
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
                                    {{ $isUpcoming ? 'View details' : 'View recap' }}
                                    <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <x-public.empty-state
                    message="No upcoming events are scheduled right now."
                    :action="route('site.events.past')"
                    action-label="Browse past events"
                />
            @endif
        </div>
    </div>
</section>
