@php
    $c = $content;
    $pathways = $c['pathways'] ?? [];
    $heading = $c['heading'] ?? 'Get Involved';
    $intro = $c['intro'] ?? 'Membership is belonging to a community carrying this work together. Choose the path that fits how you want to walk with ASNEN.';

    $defaults = [
        '/get-involved/membership' => 'Join the network and stay connected to programmes, learning, and advocacy.',
        '/get-involved/volunteer' => 'Offer your time and skills alongside families, educators, and advocates.',
        '/get-involved/partner' => 'Collaborate with ASNEN on inclusive education and community programmes.',
        '/get-involved/donate' => 'Support a programme or campaign that advances inclusion across Africa.',
    ];
@endphp

<section class="section-editorial involve-section">
    <div class="mx-auto max-w-editorial px-6 lg:px-7">
        <div class="section-head-row reveal">
            <div class="section-head">
                <span class="eyebrow mb-3 block">Walk with us</span>
                <h2>{{ $heading }}</h2>
                <p class="section-head-row__intro">{{ $intro }}</p>
            </div>
            <a href="{{ route('site.get-involved.index') }}" class="btn-secondary section-head-row__cta involve-section__cta">Explore all pathways</a>
        </div>

        <div class="reveal">
            @if(count($pathways))
                <div class="involve-grid">
                    @foreach($pathways as $index => $pathway)
                        @php
                            $url = $pathway['url'] ?? '#';
                            $label = $pathway['label'] ?? 'Learn more';
                            $desc = $pathway['desc'] ?? ($defaults[$url] ?? 'Learn how you can take part.');
                            $num = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
                        @endphp
                        <a href="{{ $url }}" class="involve-card group">
                            <span class="involve-card__num" aria-hidden="true">{{ $num }}</span>
                            <h3 class="involve-card__title">{{ $label }}</h3>
                            <p class="involve-card__desc">{{ $desc }}</p>
                            <span class="involve-card__link">
                                Get started
                                <span aria-hidden="true">→</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
