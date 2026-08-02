@php
    $c = $content;
    $limit = (int) ($c['limit'] ?? 3);
    $upcomingOnly = (bool) ($c['show_upcoming_only'] ?? false);
    $pastOnly = (bool) ($c['show_past_only'] ?? false);

    $events = \App\Models\Event::published()
        ->with('featuredImage')
        ->when($upcomingOnly, fn ($q) => $q->where('starts_at', '>=', now())->orderBy('starts_at'))
        ->when($pastOnly, fn ($q) => $q->where('starts_at', '<', now())->orderByDesc('starts_at'))
        ->when(! $upcomingOnly && ! $pastOnly, fn ($q) => $q->orderByDesc('starts_at'))
        ->limit($limit)
        ->get();

    $browseUrl = $pastOnly
        ? route('site.events.past')
        : ($upcomingOnly ? route('site.events.upcoming') : route('site.events.index'));
    $browseLabel = $pastOnly ? 'View all past events' : 'Browse events';
    $heading = $c['heading'] ?? 'Past Events';
    $intro = $c['intro'] ?? 'Conferences, webinars, and gatherings from across the ASNEN network.';
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
                                    View details
                                    <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <x-public.empty-state
                    :message="$pastOnly ? 'No past events published yet.' : 'No upcoming events at this time.'"
                    :action="route('site.events.index')"
                    action-label="Browse events"
                />
            @endif
        </div>
    </div>
</section>
