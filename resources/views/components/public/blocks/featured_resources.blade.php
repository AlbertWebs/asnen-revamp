@php
    $c = $content;
    $limit = $c['limit'] ?? 3;
    $webinars = \App\Models\Webinar::published()->latest('held_at')->limit($limit)->get();
    $heading = $c['heading'] ?? 'Featured Resources';
@endphp
<section class="section-editorial bg-ivory">
    <div class="mx-auto max-w-editorial px-6 lg:px-7">
        <div class="section-head-row reveal">
            <div class="section-head">
                <h2>{{ $heading }}</h2>
            </div>
            <a href="{{ route('site.resources.webinars') }}" class="btn-secondary section-head-row__cta">View webinar library</a>
        </div>
        <div class="reveal">
            @if($webinars->isNotEmpty())
                <div class="grid gap-6 md:grid-cols-3">
                    @foreach($webinars as $webinar)
                        <article class="rounded-lg border border-sand p-5">
                            <h3 class="font-semibold text-forest">{{ $webinar->title }}</h3>
                            @if($webinar->held_at)
                                <p class="mt-2 text-sm text-charcoal/60">{{ $webinar->held_at->format('M Y') }}</p>
                            @endif
                            @if($webinar->participant_count)
                                <p class="mt-1 text-xs text-teal">{{ $webinar->participant_count }} participants</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            @else
                <x-public.empty-state message="Webinars and resources coming soon." />
            @endif
        </div>
    </div>
</section>
