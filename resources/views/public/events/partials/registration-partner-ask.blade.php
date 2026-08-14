@php
    $initiative = config('event_pages.initiative', []);
    $tiers = $initiative['tiers'] ?? [];
    $why = $initiative['why'] ?? [];
    $contact = $initiative['contact'] ?? [];
    $showSeasonIntro = $showSeasonIntro ?? false;
    $compact = $compact ?? false;
@endphp

<section class="section-editorial {{ $compact ? '' : '' }}" id="partner-with-this-initiative">
    <div class="mx-auto max-w-editorial px-6 lg:px-7">
        <div class="event-partner-block reveal">
            <span class="eyebrow mb-3 block">Partnership</span>
            <h2>Partner With This Initiative</h2>
            <p>
                {{ $initiative['partner_intro'] ?? '' }}
                <a href="{{ route('site.get-involved.partnership-brief') }}">Download our one-page partnership brief</a>
                or
                <a href="{{ route('site.get-involved.partner') }}">get in touch</a>
                to discuss how your organisation can be involved.
            </p>
            @if($showSeasonIntro)
                @if(! empty($initiative['season_line']))
                    <p class="event-partner-block__season">{{ $initiative['season_line'] }}</p>
                @endif
                <p>{{ $initiative['intro'] ?? '' }}</p>
                <p>{{ $initiative['investment'] ?? '' }}</p>
            @endif
        </div>

        <div class="section-head reveal mt-12">
            <span class="eyebrow mb-3 block">Ways to partner</span>
            <h2>Choose a partnership tier</h2>
            <p class="section-head-row__intro">A self-serve snapshot of the ask. We will confirm inclusions when we talk.</p>
        </div>
        <ol class="who-pillars reveal">
            @foreach($tiers as $index => $tier)
                <li class="who-pillar">
                    <span class="who-pillar__num" aria-hidden="true">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    <h3 class="who-pillar__title">{{ $tier['title'] }}</h3>
                    <p class="who-pillar__body">{{ $tier['body'] }}</p>
                </li>
            @endforeach
        </ol>

        <div class="section-head reveal mt-12">
            <span class="eyebrow mb-3 block">Why partner with ASNEN</span>
            <h2>A proven, government-backed model</h2>
        </div>
        <ul class="event-highlights event-highlights--wide reveal">
            @foreach($why as $point)
                <li>{{ $point }}</li>
            @endforeach
        </ul>

        <div class="event-partner-contact reveal">
            <p class="who-identity__aside-label">Get in touch</p>
            <p class="event-partner-contact__org">{{ $contact['org'] ?? 'Africa Special Needs Education Network (ASNEN)' }}</p>
            <p>{{ $contact['city'] ?? 'Nairobi, Kenya' }} · Phone:
                @foreach(($contact['phones'] ?? []) as $i => $phone)
                    @if($i > 0)<span aria-hidden="true"> | </span>@endif
                    <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a>
                @endforeach
            </p>
            @if(! empty($contact['legal']))
                <p class="event-partner-contact__legal">{{ $contact['legal'] }}</p>
            @endif
            <div class="event-partner-contact__actions">
                <a href="{{ route('site.get-involved.partner') }}" class="btn-primary">Partner with us</a>
                <a href="{{ route('site.get-involved.partnership-brief') }}" class="btn-secondary">Download the partnership brief</a>
            </div>
        </div>
    </div>
</section>
