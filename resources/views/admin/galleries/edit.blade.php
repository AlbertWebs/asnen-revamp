@extends('layouts.admin')

@section('title', $gallery->exists ? 'Edit Gallery' : 'New Gallery')
@section('heading', $gallery->exists ? 'Edit Gallery' : 'New Gallery')

@section('content')
    @if ($gallery->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $gallery, 'routePrefix' => 'galleries'])
        </div>
    @endif

    <form method="POST" action="{{ $gallery->exists ? route('admin.galleries.update', $gallery) : route('admin.galleries.store') }}" class="admin-form admin-form--wide">
        @csrf
        @if ($gallery->exists) @method('PUT') @endif

        <div class="admin-form__body">
            <header class="admin-form__header">
                <p class="admin-form__eyebrow">Galleries</p>
                <h2 class="admin-form__title">{{ $gallery->exists ? 'Edit gallery' : 'New gallery' }}</h2>
            </header>

            <div class="admin-form__section">
                <div class="admin-field">
                    <label for="title" class="admin-label">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $gallery->title) }}" required class="admin-input">
                </div>

                <div class="admin-field">
                    <label for="slug" class="admin-label">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $gallery->slug) }}" class="admin-input">
                </div>

                <div class="admin-field">
                    <label for="description" class="admin-label">Description</label>
                    <textarea name="description" id="description" rows="4" class="admin-textarea">{{ old('description', $gallery->description) }}</textarea>
                </div>

                <div class="admin-field">
                    <label for="location" class="admin-label">Location</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $gallery->location) }}" class="admin-input">
                </div>

                <div class="admin-field">
                    <label for="gallery_date" class="admin-label">Gallery date</label>
                    <input type="date" name="gallery_date" id="gallery_date" value="{{ old('gallery_date', optional($gallery->gallery_date)->format('Y-m-d')) }}" class="admin-input">
                </div>
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn-primary">Save</button>
            </div>
        </div>
    </form>

    @if($gallery->exists)
        @php
            $namedAlbums = collect($albums ?? [])->reject(
                fn ($album) => $album->slug === 'general-gallery' || (int) $album->id === (int) $gallery->id
            )->values();
        @endphp

        @if($namedAlbums->isNotEmpty())
            <div class="admin-form admin-form--wide mt-6">
                <div class="admin-form__body">
                    <div class="admin-form__header">
                        <h2 class="admin-form__title">Albums</h2>
                        <p class="admin-form__intro">Open an album to edit it, or move photos from the gallery below.</p>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($namedAlbums as $album)
                            <a href="{{ route('admin.galleries.edit', $album) }}" class="rounded-xl border border-charcoal/10 bg-white px-4 py-3 transition hover:border-brand/40 hover:bg-brand/5">
                                <span class="block font-semibold text-charcoal">{{ $album->title }}</span>
                                <span class="mt-1 block text-sm text-charcoal/55">Edit album</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @include('admin.partials.gallery-dropzone', [
            'gallery' => $gallery,
            'albums' => $albums ?? collect(),
            'heading' => $gallery->isGeneralGallery() ? 'General Gallery' : 'Gallery images',
            'help' => 'Drag and drop photos here, or click to browse. Use Album to move a photo into another gallery.',
        ])
    @else
        <p class="admin-hint mt-6 max-w-3xl">Save the gallery first, then drop images here.</p>
    @endif
@endsection
