@php
    $c = $content;
    $image = ! empty($c['image_id'])
        ? \App\Models\MediaAsset::query()->find($c['image_id'])
        : null;
    $imageUrl = $image?->publicUrl();
    $imageAlt = $c['image_alt'] ?? ($image?->alt ?? 'ASNEN community');
    $ctaUrl = $c['cta_url'] ?? route('site.about.who-we-are');
    $ctaLabel = $c['cta_label'] ?? 'Learn more about ASNEN';
    $pillars = $c['pillars'] ?? [
        ['label' => 'Knowledge', 'text' => 'Homegrown insight that shapes inclusive practice.'],
        ['label' => 'Capacity', 'text' => 'Training families, educators, and communities.'],
        ['label' => 'Collaboration', 'text' => 'A pan-African network walking together.'],
    ];
@endphp

<section class="section-editorial bg-ivory home-about" aria-labelledby="home-about-heading">
    <div class="home-about__atmosphere" aria-hidden="true"></div>

    <div class="mx-auto max-w-editorial px-6 lg:px-7">
        <div class="home-about__grid">
            <div class="home-about__copy reveal">
                <p class="home-about__eyebrow">About ASNEN</p>
                <p class="home-about__brand">ASNEN</p>
                <h2 id="home-about-heading" class="home-about__title">{{ $c['heading'] ?? 'Who We Are' }}</h2>

                <div class="home-about__body">
                    <x-public.prose :html="$sanitizer->clean($c['body'] ?? '')" />
                </div>

                @if(is_array($pillars) && count($pillars))
                    <ul class="home-about__pillars" role="list">
                        @foreach($pillars as $pillar)
                            <li class="home-about__pillar">
                                <span class="home-about__pillar-label">{{ $pillar['label'] ?? '' }}</span>
                                <span class="home-about__pillar-text">{{ $pillar['text'] ?? '' }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="home-about__actions">
                    <a href="{{ $ctaUrl }}" class="btn-primary">{{ $ctaLabel }}</a>
                    <a href="{{ route('site.about.mission') }}" class="btn-secondary">Mission &amp; values</a>
                </div>
            </div>

            <aside class="home-about__aside reveal">
                <div class="home-about__visual">
                    @if($imageUrl)
                        <img
                            src="{{ $imageUrl }}"
                            alt="{{ $imageAlt }}"
                            class="home-about__photo"
                            width="720"
                            height="900"
                            loading="lazy"
                            decoding="async"
                        >
                    @else
                        <div class="home-about__placeholder" role="img" aria-label="About ASNEN">
                            <span class="home-about__placeholder-mark">A</span>
                        </div>
                    @endif
                    <span class="home-about__rule" aria-hidden="true"></span>
                </div>

                <blockquote class="home-about__quote">
                    <p class="home-about__quote-label">Our compass</p>
                    <p class="home-about__quote-text">Inclusion for all, in all. No child left behind.</p>
                </blockquote>
            </aside>
        </div>
    </div>
</section>
