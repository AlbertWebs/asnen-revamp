@extends('layouts.public')

@section('title', 'News & Insights | '.$siteName)
@section('meta_description', 'News and insights from ASNEN on inclusive education, community outreach, and network learning across Africa.')

@section('content')
    @php
        $featured = $articles->getCollection()->first();
        $rest = $articles->getCollection()->slice($articles->onFirstPage() && $featured ? 1 : 0)->values();
    @endphp

    <x-public.media-hero
        parent-label="Resources"
        :parent-url="route('site.resources.index')"
        current-label="News"
        eyebrow="Updates"
        title="News & insights"
        title-max="14ch"
        tagline="Stories from the network."
        :excerpt="$page?->excerpt ?? 'Updates, reflections, and learning from ASNEN programmes, partnerships, and community work.'"
        :primary-cta="['label' => 'Impact stories', 'url' => route('site.impact.stories')]"
        :secondary-cta="['label' => 'Webinar library', 'url' => route('site.resources.webinars')]"
        :images="$bannerImages ?? []"
    />

    <x-public.resources-subnav current="news" />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="who-identity reveal">
                <div class="who-identity__copy">
                    <span class="eyebrow mb-3 block">Why we publish</span>
                    <h2>Honest updates from the field and the network</h2>
                    <div class="who-identity__body">
                        <p class="text-lg leading-relaxed text-charcoal-500">
                            ASNEN shares news so families, educators, and partners can follow the work with clarity - what is happening, what we are learning, and how inclusion takes root in practice.
                        </p>
                    </div>
                </div>
                <aside class="who-identity__aside">
                    <p class="who-identity__aside-label">What you will find</p>
                    <p class="who-identity__aside-quote">Programme updates, reflections, and invitations to walk with us.</p>
                    <ul class="who-identity__aside-list">
                        <li>Verified, published articles only</li>
                        <li>Clear dates and categories</li>
                        <li>Links into stories, events, and programmes</li>
                    </ul>
                    <a href="{{ route('site.impact.overview') }}" class="who-identity__aside-link">
                        Visit Impact
                        <span aria-hidden="true">→</span>
                    </a>
                </aside>
            </div>
        </div>
    </section>

    @if($featured && $articles->onFirstPage())
        <section class="section-editorial bg-sand">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="section-head-row reveal">
                    <div class="section-head">
                        <span class="eyebrow mb-3 block">Latest</span>
                        <h2>Featured insight</h2>
                        <p class="section-head-row__intro">Start with the most recent published update from ASNEN.</p>
                    </div>
                    <a href="{{ route('site.resources.news.show', $featured->slug) }}" class="btn-secondary section-head-row__cta">Read article</a>
                </div>

                <article class="news-feature reveal">
                    <a href="{{ route('site.resources.news.show', $featured->slug) }}" class="news-feature__media">
                        <x-public.media-frame
                            :asset="$featured->featuredImage"
                            :alt="$featured->featuredImage?->alt ?? $featured->title"
                            ratio="16/9"
                            rounded="rounded-2xl"
                            label="Article image"
                        />
                    </a>
                    <div class="news-feature__copy">
                        <p class="news-feature__meta">
                            {{ collect([
                                $featured->category ? \Illuminate\Support\Str::headline($featured->category) : 'Insight',
                                $featured->published_at?->format('d M Y'),
                                $featured->reading_time_minutes ? $featured->reading_time_minutes.' min read' : null,
                            ])->filter()->implode(' · ') }}
                        </p>
                        <h3 class="news-feature__title">
                            <a href="{{ route('site.resources.news.show', $featured->slug) }}">{{ $featured->title }}</a>
                        </h3>
                        @if($featured->excerpt)
                            <p class="news-feature__excerpt">{{ $featured->excerpt }}</p>
                        @endif
                        <a href="{{ route('site.resources.news.show', $featured->slug) }}" class="news-feature__link">
                            Continue reading
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </article>
            </div>
        </section>
    @endif

    <section class="section-editorial {{ $featured && $articles->onFirstPage() ? '' : 'bg-sand' }}">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head-row reveal">
                <div class="section-head">
                    <span class="eyebrow mb-3 block">All articles</span>
                    <h2>News from the network</h2>
                    <p class="section-head-row__intro">
                        @if($articles->total() > 0)
                            {{ $articles->total() }} published {{ \Illuminate\Support\Str::plural('article', $articles->total()) }}.
                        @else
                            News articles will appear here once published.
                        @endif
                    </p>
                </div>
            </div>

            @if($articles->isEmpty())
                <div class="reveal mt-8">
                    <x-public.empty-state
                        message="News articles will appear here once published."
                        :action="route('site.impact.stories')"
                        action-label="Browse impact stories"
                    />
                </div>
            @else
                <div class="impact-story-grid reveal mt-8">
                    @foreach(($featured && $articles->onFirstPage() ? $rest : $articles) as $article)
                        <article class="impact-story-card">
                            <a href="{{ route('site.resources.news.show', $article->slug) }}" class="impact-story-card__media">
                                <x-public.media-frame
                                    :asset="$article->featuredImage"
                                    :alt="$article->featuredImage?->alt ?? $article->title"
                                    ratio="16/9"
                                    rounded="rounded-none"
                                    label="Article image"
                                />
                            </a>
                            <div class="impact-story-card__body">
                                <p class="impact-story-card__meta">
                                    {{ collect([
                                        $article->category ? \Illuminate\Support\Str::headline($article->category) : 'Insight',
                                        $article->published_at?->format('d M Y'),
                                    ])->filter()->implode(' · ') }}
                                </p>
                                <h3 class="impact-story-card__title">
                                    <a href="{{ route('site.resources.news.show', $article->slug) }}">{{ $article->title }}</a>
                                </h3>
                                @if($article->excerpt)
                                    <p class="impact-story-card__summary">{{ $article->excerpt }}</p>
                                @endif
                                <a href="{{ route('site.resources.news.show', $article->slug) }}" class="impact-story-card__link">
                                    Read article
                                    <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="reveal">
                    <x-public.pagination :paginator="$articles" />
                </div>
            @endif
        </div>
    </section>

    <section class="section-editorial bg-sand">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head reveal">
                <span class="eyebrow mb-3 block">Keep exploring</span>
                <h2>More ways into ASNEN</h2>
                <p class="section-head-row__intro">Follow the work through stories, learning, and programmes.</p>
            </div>

            <div class="who-explore reveal">
                <a href="{{ route('site.impact.stories') }}" class="who-explore__item">
                    <span class="who-explore__label">Impact stories</span>
                    <span class="who-explore__desc">Evidence-led narratives from the field</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('site.resources.webinars') }}" class="who-explore__item">
                    <span class="who-explore__label">Webinar library</span>
                    <span class="who-explore__desc">Recorded learning sessions</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('site.events.upcoming') }}" class="who-explore__item">
                    <span class="who-explore__label">Upcoming events</span>
                    <span class="who-explore__desc">Join the next gathering</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('site.get-involved.index') }}" class="who-explore__item">
                    <span class="who-explore__label">Get involved</span>
                    <span class="who-explore__desc">Walk with the network</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </section>

    <x-public.cta-band
        heading="Stay close to the work"
        text="Membership keeps you connected to ASNEN news, webinars, and programme updates."
        :primary-cta="['label' => 'Become a member', 'url' => route('site.get-involved.membership')]"
        :secondary-cta="['label' => 'Contact us', 'url' => route('site.contact')]"
    />
@endsection
