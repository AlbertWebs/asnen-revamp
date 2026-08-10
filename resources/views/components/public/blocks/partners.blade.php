@php
    $c = $content;
    $partners = \App\Models\Partner::published()
        ->where('verification_status', \App\Enums\VerificationStatus::Verified)
        ->orderBy('sort_order')
        ->with('logo')
        ->get();
    $heading = $c['heading'] ?? 'Our Collaborators';
@endphp
<section class="section-editorial bg-ivory">
    <div class="mx-auto max-w-editorial px-6 lg:px-7">
        <div class="section-head-row reveal">
            <div class="section-head">
                <h2>{{ $heading }}</h2>
            </div>
            <a href="{{ url('/about/partners') }}" class="btn-secondary section-head-row__cta">View all partners</a>
        </div>
    </div>
    <div class="reveal mt-6">
        <x-public.partner-logos :partners="$partners" layout="marquee" />
    </div>
</section>
