@php
    $c = $content;
    $slugs = $c['program_slugs'] ?? [];
    $programs = \App\Models\Program::published()
        ->when($slugs, fn ($q) => $q->whereIn('slug', $slugs))
        ->orderBy('sort_order')
        ->get();
    $heading = $c['heading'] ?? 'We believe we can reach more families with you.';
@endphp
<section class="section-editorial bg-ivory">
    <div class="mx-auto max-w-editorial px-6 lg:px-7">
        <div class="section-head-row reveal">
            <div class="section-head">
                <span class="eyebrow mb-3 block">What we do</span>
                <h2>{{ $heading }}</h2>
            </div>
            <a href="{{ route('site.programs.index') }}" class="btn-secondary section-head-row__cta">View all programs</a>
        </div>
        <div class="reveal">
            <x-public.program-pillars :programs="$programs" />
        </div>
    </div>
</section>
