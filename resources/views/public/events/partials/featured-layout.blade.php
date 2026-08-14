@php
    $highlights = $profile['highlights'] ?? [];
    $steps = $profile['steps'] ?? [];
    $primaryCta = $profile['primary_cta'] ?? null;
    $secondaryCta = $profile['secondary_cta'] ?? null;
    if ($primaryCta && str_starts_with($primaryCta['url'], '/')) {
        $primaryCta['url'] = url($primaryCta['url']);
    }
    if ($secondaryCta && str_starts_with($secondaryCta['url'], '/')) {
        $secondaryCta['url'] = url($secondaryCta['url']);
    }
    $when = $event->starts_at
        ? ($event->ends_at
            ? $event->starts_at->format('l, j F Y').' · '.$event->starts_at->format('g:i A').'-'.$event->ends_at->format('g:i A')
            : $event->starts_at->format('l, j F Y · g:i A'))
        : null;
    $where = $profile['location'] ?? ($event->is_online ? 'Online' : $event->venue);
    $recapImages = collect($bannerImages ?? [])
        ->filter()
        ->values();
    if ($recapImages->isEmpty() && $event->featuredImage) {
        $recapImages = collect([$event->featuredImage]);
    }
@endphp

@if($event->isPast())
    <section class="section-editorial bg-sand" aria-labelledby="event-recap-heading">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head-row reveal">
                <div class="section-head">
                    <span class="eyebrow mb-3 block">Past event</span>
                    <h2 id="event-recap-heading">Event recap</h2>
                    <p class="section-head-row__intro">Photos and notes from {{ $event->title }}. This page stays as the public record after the day.</p>
                </div>
            </div>
            @if($recapImages->isNotEmpty())
                <div class="impact-story-grid reveal mt-8">
                    @foreach($recapImages->take(6) as $asset)
                        <figure class="impact-story-card">
                            <div class="impact-story-card__media">
                                <x-public.media-frame
                                    :asset="$asset"
                                    :alt="(is_object($asset) ? ($asset->alt ?? $event->title) : ($asset['alt'] ?? $event->title))"
                                    ratio="16/9"
                                    rounded="rounded-none"
                                    label="Event photo"
                                />
                            </div>
                        </figure>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endif

<section class="section-editorial" aria-labelledby="event-about-heading">
    <div class="mx-auto max-w-editorial px-6 lg:px-7">
        <div class="program-detail program-detail--deliver reveal">
            <div class="program-detail__main">
                <header class="program-detail__header">
                    <span class="eyebrow program-detail__eyebrow">About this event</span>
                    <h2 id="event-about-heading">What to expect</h2>
                    @if($event->body || $event->summary)
                        <div class="program-detail__body">
                            <x-public.prose :html="$sanitizer->clean($event->body ?? $event->summary)" />
                        </div>
                    @endif
                </header>

                @if($steps)
                    <ul class="program-deliverables" aria-label="{{ $profile['steps_heading'] ?? 'How this event takes shape' }}">
                        @foreach($steps as $index => $item)
                            <li class="program-deliverable" style="--deliver-i: {{ $index }};">
                                <span class="program-deliverable__index" aria-hidden="true">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                <div class="program-deliverable__copy">
                                    <h3 class="program-deliverable__title">{{ $item['title'] }}</h3>
                                    <p class="program-deliverable__body">{{ $item['body'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="program-detail__side">
                <aside class="event-detail__aside">
                    <p class="who-identity__aside-label">Our commitment</p>
                    @if($highlights)
                        <ul class="event-highlights">
                            @foreach($highlights as $point)
                                <li>{{ $point }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <dl class="event-detail__facts mt-6">
                        <div>
                            <dt>Type</dt>
                            <dd>{{ $typeLabel }}</dd>
                        </div>
                        @if($when)
                            <div>
                                <dt>When</dt>
                                <dd>{{ $when }}</dd>
                            </div>
                        @endif
                        @if($where)
                            <div>
                                <dt>Where</dt>
                                <dd>{{ $where }}</dd>
                            </div>
                        @endif
                    </dl>
                    @if(! $event->isPast())
                    <div class="event-calendar-actions">
                        <p class="who-identity__aside-label">Add to calendar</p>
                        <div class="event-calendar-actions__row">
                            @if($event->googleCalendarUrl())
                                <a href="{{ $event->googleCalendarUrl() }}" class="btn-secondary" target="_blank" rel="noopener noreferrer">Google</a>
                            @endif
                            @if($event->outlookCalendarUrl())
                                <a href="{{ $event->outlookCalendarUrl() }}" class="btn-secondary" target="_blank" rel="noopener noreferrer">Outlook</a>
                            @endif
                            <a href="{{ route('site.events.calendar', $event->slug) }}" class="btn-secondary">ICS</a>
                        </div>
                    </div>
                    @if($primaryCta)
                        <a href="{{ $primaryCta['url'] }}" class="btn-primary mt-5 inline-flex w-full justify-center">{{ $primaryCta['label'] }}</a>
                    @endif
                    @if($secondaryCta)
                        <a href="{{ $secondaryCta['url'] }}" class="btn-secondary mt-3 inline-flex w-full justify-center">{{ $secondaryCta['label'] }}</a>
                    @endif
                    @else
                        <a href="{{ route('site.events.upcoming') }}" class="btn-primary mt-5 inline-flex w-full justify-center">Upcoming events</a>
                    @endif
                </aside>
            </div>
        </div>
    </div>
</section>

@if($steps)
    <section class="section-editorial bg-sand">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head-row reveal">
                <div class="section-head">
                    <span class="eyebrow mb-3 block">{{ $profile['steps_eyebrow'] ?? 'The programme' }}</span>
                    <h2>{{ $profile['steps_heading'] ?? 'How this event takes shape' }}</h2>
                </div>
            </div>

            <ol class="who-pillars reveal">
                @foreach($steps as $index => $item)
                    <li class="who-pillar">
                        <span class="who-pillar__num" aria-hidden="true">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="who-pillar__title">{{ $item['title'] }}</h3>
                        <p class="who-pillar__body">{{ $item['body'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
@endif

@if($companionEvent)
    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head reveal">
                <span class="eyebrow mb-3 block">Two-part initiative</span>
                <h2>{{ $profile['companion_label'] ?? $companionEvent->title }}</h2>
                @if(! empty($profile['companion_intro']))
                    <p class="section-head-row__intro">{{ $profile['companion_intro'] }}</p>
                @endif
            </div>
            <div class="who-explore reveal">
                <a href="{{ route('site.events.show', $companionEvent->slug) }}" class="who-explore__item">
                    <span class="who-explore__label">{{ $companionEvent->title }}</span>
                    <span class="who-explore__desc">{{ \Illuminate\Support\Str::limit($companionEvent->summary, 120) }}</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </section>
@endif

@if(($profile['show_komolion'] ?? false) && $komolionStory)
    <section class="section-editorial bg-sand">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head-row reveal">
                <div class="section-head">
                    <span class="eyebrow mb-3 block">From the field</span>
                    <h2>We have done this before</h2>
                    <p class="section-head-row__intro">Four registration events delivered to date, most recently in Embakasi (March 2026). Komolion 2023 remains a published case study of the same model.</p>
                </div>
                <a href="{{ route('site.impact.stories.show', $komolionStory->slug) }}" class="btn-secondary section-head-row__cta">Read the story</a>
            </div>
            <div class="impact-story-grid reveal">
                <article class="impact-story-card">
                    <a href="{{ $komolionStory->publicUrl() }}" class="impact-story-card__media">
                        <x-public.media-frame
                            :asset="$komolionStory->featuredImage"
                            :alt="$komolionStory->featuredImage?->alt ?? $komolionStory->title"
                            ratio="16/9"
                            rounded="rounded-none"
                            label="Story photo"
                        />
                    </a>
                    <div class="impact-story-card__body">
                        <h3 class="impact-story-card__title">
                            <a href="{{ $komolionStory->publicUrl() }}">{{ $komolionStory->title }}</a>
                        </h3>
                        @if($komolionStory->summary)
                            <p class="impact-story-card__summary">{{ \Illuminate\Support\Str::limit($komolionStory->summary, 160) }}</p>
                        @endif
                        <a href="{{ $komolionStory->publicUrl() }}" class="impact-story-card__link">
                            Read story
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </section>
@endif

@if($profile['show_partner_ask'] ?? false)
    @include('public.events.partials.registration-partner-ask', [
        'showSeasonIntro' => true,
    ])
@endif

