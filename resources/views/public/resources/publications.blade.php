@extends('layouts.public')

@section('title', 'Reports & Publications | '.$siteName)

@section('content')
    <section class="impact-hero">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="impact-hero__inner reveal">
                <x-public.breadcrumbs :items="[
                    ['label' => 'Resources', 'url' => route('site.resources.index')],
                    ['label' => 'Publications'],
                ]" />
                <span class="eyebrow mt-6 block">Resources</span>
                <h1 class="impact-hero__title">Reports &amp; Publications</h1>
                <p class="impact-hero__excerpt">Download ASNEN reports, toolkits, and publications. Impact reports are also listed under Impact.</p>
                <div class="impact-hero__actions">
                    <a href="{{ route('site.impact.reports') }}" class="btn-primary">Impact reports</a>
                </div>
            </div>
        </div>
    </section>

    <x-public.section>
        @if($publications->isEmpty())
            <x-public.empty-state message="Publications will appear here once uploaded and verified." />
        @else
            <div class="report-grid reveal">
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
                                        Download PDF
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
    </x-public.section>
@endsection
