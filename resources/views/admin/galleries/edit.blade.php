@extends('layouts.admin')

@section('title', $gallery->exists ? 'Edit Gallery' : 'New Gallery')
@section('heading', $gallery->exists ? 'Edit Gallery' : 'New Gallery')

@section('content')
    <form method="POST" action="{{ $gallery->exists ? route('admin.galleries.update', $gallery) : route('admin.galleries.store') }}">
        @csrf
        @if ($gallery->exists) @method('PUT') @endif

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            @include('admin.partials.publish-buttons', ['model' => $gallery, 'routePrefix' => 'galleries'])
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
        </div>

        <div class="max-w-3xl rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <label for="title" class="block text-sm font-medium text-charcoal-700">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $gallery->title) }}" required class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">

            <label for="slug" class="mt-4 block text-sm font-medium text-charcoal-700">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $gallery->slug) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">

            <label for="description" class="mt-4 block text-sm font-medium text-charcoal-700">Description</label>
            <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('description', $gallery->description) }}</textarea>

            <label for="location" class="mt-4 block text-sm font-medium text-charcoal-700">Location</label>
            <input type="text" name="location" id="location" value="{{ old('location', $gallery->location) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">

            <label for="gallery_date" class="mt-4 block text-sm font-medium text-charcoal-700">Gallery Date</label>
            <input type="date" name="gallery_date" id="gallery_date" value="{{ old('gallery_date', optional($gallery->gallery_date)->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
        </div>
    </form>

    @if($gallery->exists)
        @include('admin.partials.gallery-dropzone', [
            'gallery' => $gallery,
            'heading' => 'Gallery images',
            'help' => 'Drag and drop all photos here, or click to browse. Captions show under each photo on the public gallery.',
        ])
    @else
        <p class="mt-6 max-w-3xl text-sm text-charcoal-500">Save the gallery first, then drop images here.</p>
    @endif
@endsection
