@extends('layouts.public')

@section('title', $publication->title.' | '.$siteName)
@section('meta_description', $publication->abstract)

@section('content')
    <section class="impact-hero">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="impact-hero__inner reveal">
                <x-public.breadcrumbs :items="[
                    ['label' => 'Resources', 'url' => route('site.resources.index')],
                    ['label' => 'Publications', 'url' => route('site.resources.publications')],
                    ['label' => $publication->title],
                ]" />
                <span class="eyebrow mt-6 block">{{ $publication->categoryLabel() }}</span>
                <h1 class="impact-hero__title" style="max-width: 20ch;">{{ $publication->title }}</h1>
                @if($publication->year)
                    <p class="impact-hero__excerpt">{{ $publication->year }}</p>
                @endif
            </div>
        </div>
    </section>

    <x-public.section>
        <div class="grid gap-10 lg:grid-cols-[14rem_minmax(0,1fr)]">
            <div class="reveal overflow-hidden rounded-xl border border-charcoal/10 bg-sand">
                <x-public.media-frame
                    :asset="$publication->cover"
                    :alt="$publication->cover?->alt ?? $publication->title"
                    ratio="3/4"
                    rounded="rounded-none"
                    label="Report cover"
                />
            </div>
            <div class="reveal">
                @if($publication->abstract)
                    <p class="text-lg leading-relaxed text-charcoal/80">{{ $publication->abstract }}</p>
                @endif
                <div class="mt-8 flex flex-wrap items-center gap-3">
                    @if($publication->file)
                        <a href="{{ route('site.resources.publications.download', $publication->slug) }}" class="btn-primary">
                            Download PDF
                            @if($publication->fileSizeLabel())
                                <span class="ml-2 opacity-90">({{ $publication->fileSizeLabel() }})</span>
                            @endif
                        </a>
                        <p class="text-sm text-charcoal/60">{{ number_format($publication->download_count) }} downloads</p>
                    @else
                        <x-public.empty-state message="This publication does not have a downloadable file yet." />
                    @endif
                </div>
                <p class="mt-8">
                    <a href="{{ route('site.impact.reports') }}" class="font-mono text-[0.7rem] font-bold uppercase tracking-wider text-brand hover:underline">← Back to Impact Reports</a>
                </p>
            </div>
        </div>
    </x-public.section>
@endsection
