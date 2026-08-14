@extends('layouts.public')

@section('title', 'Reports & Publications | '.$siteName)
@section('meta_description', $page?->excerpt ?? 'Download ASNEN reports, toolkits, and publications documenting programmes, learning, and verified progress.')

@section('content')
    <x-public.media-hero
        parent-label="Resources"
        :parent-url="route('site.resources.index')"
        current-label="Publications"
        eyebrow="Evidence & publications"
        title="Reports & Publications"
        title-max="16ch"
        :excerpt="$page?->excerpt ?? 'Download ASNEN reports, toolkits, and publications documenting programmes, learning, and verified progress.'"
        :primary-cta="['label' => 'Toolkits & guides', 'url' => route('site.resources.toolkits')]"
        :secondary-cta="['label' => 'Impact stories', 'url' => route('site.impact.stories')]"
        :images="$bannerImages ?? []"
    />

    <x-public.resources-subnav current="publications" />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head-row reveal">
                <div class="section-head">
                    <span class="eyebrow mb-3 block">Downloads</span>
                    <h2>Reports you can download</h2>
                    <p class="section-head-row__intro">Annual reports, conference reports, toolkits, and other publications from ASNEN programmes and learning.</p>
                </div>
                <a href="{{ route('site.resources.toolkits') }}" class="btn-secondary section-head-row__cta">Toolkits &amp; guides</a>
            </div>

            <div class="reveal mt-8">
                @if($publications->isEmpty())
                    <x-public.empty-state message="Publications will appear here once uploaded and verified." />
                @else
                    <div class="report-grid">
                        @foreach($publications as $publication)
                            <article class="report-card">
                                <div class="report-card__cover">
                                    <x-public.publication-thumb :publication="$publication" />
                                </div>
                                <div class="report-card__body">
                                    <div class="report-card__meta">
                                        <span class="report-card__type">{{ $publication->categoryLabel() }}</span>
                                        @if($publication->year)
                                            <span class="report-card__year">{{ $publication->year }}</span>
                                        @endif
                                    </div>
                                    <h2 class="report-card__title">
                                        <a href="{{ route('site.resources.publications.show', $publication->slug) }}">{{ $publication->title }}</a>
                                    </h2>
                                    @if($publication->abstract)
                                        <p class="report-card__summary">{{ $publication->abstract }}</p>
                                    @endif
                                    <div class="report-card__actions">
                                        @if($publication->file)
                                            <a href="{{ route('site.resources.publications.download', $publication->slug) }}" class="btn-primary report-card__download">
                                                {{ $publication->downloadLabel() }}
                                                @if($publication->fileSizeLabel())
                                                    <span class="report-card__size">{{ $publication->fileSizeLabel() }}</span>
                                                @endif
                                            </a>
                                        @endif
                                        <a href="{{ route('site.resources.publications.show', $publication->slug) }}{{ ! $publication->file ? '#request-file' : '' }}" class="report-card__details">
                                            {{ ! $publication->file ? 'Request file' : 'View details' }}
                                            <span aria-hidden="true">→</span>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <x-public.pagination :paginator="$publications" />
                @endif
            </div>
        </div>
    </section>

    <x-public.cta-band
        heading="Looking for stories behind the numbers?"
        text="Explore Komolion and other impact narratives from across the network."
        :primary-cta="['label' => 'Success stories', 'url' => route('site.impact.stories')]"
        :secondary-cta="['label' => 'Impact overview', 'url' => route('site.impact.overview')]"
    />
@endsection
