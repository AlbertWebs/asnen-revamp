@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-lg border border-charcoal-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-charcoal-500">Draft content</p>
            <p class="mt-2 text-3xl font-bold text-charcoal-900">{{ number_format($draftCount) }}</p>
        </div>
        <div class="rounded-lg border border-charcoal-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-charcoal-500">Published</p>
            <p class="mt-2 text-3xl font-bold text-forest-700">{{ number_format($publishedCount) }}</p>
        </div>
        <div class="rounded-lg border border-charcoal-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-charcoal-500">Pending safeguarding</p>
            <p class="mt-2 text-3xl font-bold text-amber-700">{{ number_format($pendingSafeguarding) }}</p>
        </div>
        <div class="rounded-lg border border-charcoal-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-charcoal-500">New form submissions</p>
            <p class="mt-2 text-3xl font-bold text-charcoal-900">{{ number_format($newSubmissions) }}</p>
        </div>
    </div>

    <div class="mt-8 rounded-lg border border-charcoal-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-charcoal-900">Quick links</h2>
        <div class="mt-4 flex flex-wrap gap-3">
            @can('pages.create')
                <a href="{{ route('admin.pages.create') }}" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">New page</a>
            @endcan
            @can('media.upload')
                <a href="{{ route('admin.media.create') }}" class="rounded-md border border-charcoal-300 bg-white px-4 py-2 text-sm font-medium text-charcoal-700 hover:bg-charcoal-50">Upload media</a>
            @endcan
            @can('form_submissions.view')
                <a href="{{ route('admin.form-submissions.index') }}" class="rounded-md border border-charcoal-300 bg-white px-4 py-2 text-sm font-medium text-charcoal-700 hover:bg-charcoal-50">Review submissions</a>
            @endcan
        </div>
    </div>
@endsection
