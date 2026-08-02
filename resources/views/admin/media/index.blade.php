@extends('layouts.admin')

@section('title', 'Media Library')
@section('heading', 'Media Library')

@section('content')
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <p class="max-w-2xl text-sm text-charcoal-600">
            Upload images here, then attach them on Programs, Stories, Events, Partners, Team, Publications, or Home page blocks.
        </p>
        @can('media.upload')
            <a href="{{ route('admin.media.create') }}" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Upload</a>
        @endcan
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @forelse ($assets as $asset)
            <div class="overflow-hidden rounded-lg border border-charcoal-200 bg-white shadow-sm">
                <div class="aspect-video bg-charcoal-100 flex items-center justify-center text-xs text-charcoal-500">
                    @if (str_starts_with($asset->mime ?? '', 'image/'))
                        <img src="{{ asset('storage/'.$asset->path) }}" alt="{{ e($asset->alt ?? '') }}" class="h-full w-full object-cover">
                    @else
                        {{ $asset->mime }}
                    @endif
                </div>
                <div class="p-3">
                    <p class="truncate text-sm font-medium text-charcoal-900">{{ $asset->filename }}</p>
                    <p class="text-xs text-charcoal-500">{{ $asset->consent_status?->value ?? $asset->consent_status }}</p>
                    @can('media.update')
                        <a href="{{ route('admin.media.edit', $asset) }}" class="mt-2 inline-block text-sm text-forest-700 hover:underline">Edit</a>
                    @endcan
                </div>
            </div>
        @empty
            <p class="col-span-full py-8 text-center text-sm text-charcoal-500">No media uploaded yet.</p>
        @endforelse
    </div>

    <div class="mt-4">{{ $assets->links() }}</div>
@endsection
