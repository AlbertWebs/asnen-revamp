@extends('layouts.public')

@section('title', ($page?->seoMeta?->title ?? $page?->title ?? 'Impact by Region').' | '.$siteName)
@section('meta_description', $page?->seoMeta?->description ?? $page?->excerpt ?? 'Where ASNEN\'s work reaches communities across Kenya and beyond.')

@section('content')
    @php
        $mapRegions = $regions
            ->filter(fn ($region) => $region->hasCoordinates())
            ->values()
            ->map->toMapPayload()
            ->all();
        $introHtml = $page?->blocks?->firstWhere('type', 'rich_text')?->content['body'] ?? null;
    @endphp

    <x-public.about-hero
        breadcrumb="Impact"
        :breadcrumb-url="route('site.impact.overview')"
        current-label="Regions"
        :title="$page?->title ?? 'Impact by region'"
        title-max="14ch"
        tagline="Where belonging takes root."
        :excerpt="$page?->excerpt ?? 'Explore the places ASNEN has walked with families, schools, and partners.'"
        :primary-cta="['label' => 'Browse stories', 'url' => route('site.impact.stories')]"
        :secondary-cta="['label' => 'View reports', 'url' => route('site.impact.reports')]"
    />

    <x-public.impact-subnav current="regions" />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head-row reveal">
                <div class="section-head">
                    <span class="eyebrow mb-3 block">Impact map</span>
                    <h2>Communities we have reached</h2>
                    <p class="section-head-row__intro">Select a place on the map or in the list to learn how ASNEN's work shows up on the ground.</p>
                </div>
                @if($regions->isNotEmpty())
                    <p class="section-head-row__cta impact-map-count">{{ $regions->count() }} {{ \Illuminate\Support\Str::plural('region', $regions->count()) }}</p>
                @endif
            </div>

            @if($regions->isEmpty())
                <div class="reveal mt-8">
                    <x-public.empty-state
                        message="Regional impact locations will appear here once published from the admin panel."
                        :action="route('site.impact.overview')"
                        action-label="Back to Impact"
                    />
                </div>
            @else
                <div
                    class="impact-map reveal mt-8"
                    x-data="impactRegionsMap({
                        regions: @js($mapRegions),
                        center: [-0.8, 37.6],
                        zoom: 6,
                    })"
                >
                    <div class="impact-map__layout">
                        <div class="impact-map__canvas-wrap">
                            <div class="impact-map__canvas" x-ref="map" role="region" aria-label="Map of ASNEN impact regions"></div>
                            <p class="impact-map__hint">Click a pin or use the list. Scroll to zoom after clicking the map.</p>
                        </div>

                        <div class="impact-map__list" role="list">
                            @foreach($regions as $region)
                                @php
                                    $hasPin = $region->hasCoordinates();
                                @endphp
                                <button
                                    type="button"
                                    role="listitem"
                                    class="impact-map__item"
                                    :class="activeId === {{ $region->id }} && 'is-active'"
                                    @if($hasPin)
                                        @click="selectRegion(@js($region->toMapPayload()))"
                                    @endif
                                    @disabled(! $hasPin)
                                >
                                    <span class="impact-map__item-top">
                                        <span class="impact-map__item-name">{{ $region->name }}</span>
                                        @if($region->is_featured)
                                            <span class="impact-map__item-badge">Featured</span>
                                        @endif
                                    </span>
                                    @if($region->impact_label)
                                        <span class="impact-map__item-label">{{ $region->impact_label }}</span>
                                    @elseif($region->country)
                                        <span class="impact-map__item-label">{{ $region->country }}</span>
                                    @endif
                                    @if($region->description)
                                        <span class="impact-map__item-desc">{{ \Illuminate\Support\Str::limit($region->description, 110) }}</span>
                                    @endif
                                    @if($region->link_url)
                                        <a
                                            href="{{ $region->link_url }}"
                                            class="impact-map__item-link"
                                            @click.stop
                                        >{{ $region->link_label ?: 'Learn more' }} <span aria-hidden="true">→</span></a>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @if(!empty($introHtml))
        <section class="section-editorial bg-sand">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="who-identity reveal">
                    <div class="who-identity__copy">
                        <span class="eyebrow mb-3 block">How we share geography</span>
                        <h2>Verified places, honest reach</h2>
                        <div class="who-identity__body">
                            <x-public.prose :html="$sanitizer->clean($introHtml)" />
                        </div>
                    </div>
                    <aside class="who-identity__aside">
                        <p class="who-identity__aside-label">Updated from admin</p>
                        <p class="who-identity__aside-quote">Every pin is published when the place and story are ready.</p>
                        <ul class="who-identity__aside-list">
                            <li>Coordinates set by ASNEN editors</li>
                            <li>Descriptions kept concise and accurate</li>
                            <li>Links to case studies where available</li>
                        </ul>
                    </aside>
                </div>
            </div>
        </section>
    @endif

    <x-public.cta-band
        heading="Bring this work to more places"
        text="Partnerships and membership help ASNEN walk with more communities across Africa."
        :primary-cta="['label' => 'Partner with us', 'url' => route('site.get-involved.partner')]"
        :secondary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
    />
@endsection
