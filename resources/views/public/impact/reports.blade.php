@extends('layouts.public')

@section('title', ($page->title ?? 'Impact Reports').' | '.$siteName)
@section('meta_description', $page->excerpt ?? 'Download ASNEN impact and conference reports.')

@section('content')
    <x-public.media-hero
        parent-label="Impact"
        :parent-url="route('site.impact.overview')"
        current-label="Reports"
        eyebrow="Evidence & publications"
        :title="$page->title ?? 'Impact Reports'"
        title-max="14ch"
        :excerpt="$page->excerpt"
        :body-html="!empty($introHtml) ? $sanitizer->clean($introHtml) : null"
        :primary-cta="['label' => 'All publications', 'url' => route('site.resources.publications')]"
        :secondary-cta="['label' => 'Impact stories', 'url' => route('site.impact.stories')]"
        :images="$bannerImages ?? []"
    />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head-row reveal">
                <div class="section-head">
                    <span class="eyebrow mb-3 block">Downloads</span>
                    <h2>Reports you can download</h2>
                    <p class="section-head-row__intro">Reports from ASNEN conferences, programmes, and annual updates.</p>
                </div>
                <a href="{{ route('site.resources.publications') }}" class="btn-secondary section-head-row__cta">All publications</a>
            </div>

            <div class="reveal">
                @if($reports->isEmpty())
                    <x-public.empty-state
                        message="Impact reports will appear here once available."
                        :action="route('site.impact.overview')"
                        action-label="Back to Impact"
                    />
                @else
                    <div class="report-grid">
                        @foreach($reports as $report)
                            <article class="report-card">
                                <div class="report-card__cover">
                                    <x-public.publication-thumb
                                        :publication="$report"
                                        label="Report cover"
                                    />
                                </div>
                                <div class="report-card__body">
                                    <div class="report-card__meta">
                                        <span class="report-card__type">{{ $report->categoryLabel() }}</span>
                                        @if($report->year)
                                            <span class="report-card__year">{{ $report->year }}</span>
                                        @endif
                                    </div>
                                    <h3 class="report-card__title">
                                        <a href="{{ route('site.resources.publications.show', $report->slug) }}">{{ $report->title }}</a>
                                    </h3>
                                    @if($report->abstract)
                                        <p class="report-card__summary">{{ $report->abstract }}</p>
                                    @endif
                                    <div class="report-card__actions">
                                        @if($report->file)
                                            <a
                                                href="{{ route('site.resources.publications.download', $report->slug) }}"
                                                class="btn-primary report-card__download"
                                            >
                                                {{ $report->downloadLabel() }}
                                                @if($report->fileSizeLabel())
                                                    <span class="report-card__size">{{ $report->fileSizeLabel() }}</span>
                                                @endif
                                            </a>
                                        @else
                                            <span class="report-card__unavailable">File coming soon</span>
                                        @endif
                                        <a href="{{ route('site.resources.publications.show', $report->slug) }}" class="report-card__details">
                                            View details
                                            <span aria-hidden="true">→</span>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <x-public.cta-band
        heading="Looking for stories behind the numbers?"
        text="Explore the Komolion case study and other impact narratives from across the network."
        :primary-cta="['label' => 'Komolion story', 'url' => route('site.impact.komolion')]"
        :secondary-cta="['label' => 'Impact overview', 'url' => route('site.impact.overview')]"
    />
@endsection
