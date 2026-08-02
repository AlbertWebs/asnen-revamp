@php
    $c = $content;
    $limit = (int) ($c['limit'] ?? 6);
    $members = \App\Models\TeamMember::published()
        ->orderBy('sort_order')
        ->orderBy('name')
        ->with('photo')
        ->limit($limit)
        ->get();
    $heading = $c['heading'] ?? 'Leadership & Team';
@endphp
<section class="section-editorial bg-ivory">
    <div class="mx-auto max-w-editorial px-6 lg:px-7">
        <div class="section-head-row reveal">
            <div class="section-head">
                <span class="eyebrow mb-3 block">Meet Our Team</span>
                <h2>{{ $heading }}</h2>
            </div>
            <a href="{{ url('/about/leadership') }}" class="btn-secondary section-head-row__cta">Meet the full team</a>
        </div>

        <div class="reveal">
            @if($members->isNotEmpty())
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                    @foreach($members as $member)
                        <article class="text-center">
                            <div class="mx-auto w-36">
                                <x-public.media-frame
                                    :asset="$member->photo"
                                    :alt="$member->photo?->alt ?? $member->name"
                                    ratio="1/1"
                                    rounded="rounded-full"
                                    label="Photo"
                                />
                            </div>
                            <h3 class="mt-4 font-display text-lg font-semibold text-forest">{{ $member->name }}</h3>
                            @if($member->title_role)
                                <p class="mt-1 font-mono text-[0.7rem] uppercase tracking-wide text-brand">{{ $member->title_role }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            @else
                <x-public.empty-state message="Team profiles will appear here once published." :action="url('/about/leadership')" action-label="Leadership page" />
            @endif
        </div>
    </div>
</section>
