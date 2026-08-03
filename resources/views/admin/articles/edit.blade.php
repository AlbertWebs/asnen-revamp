@extends('layouts.admin')

@section('title', $article->exists ? 'Edit' : 'New')
@section('heading', $article->exists ? 'Edit' : 'New')

@section('content')
    @if ($article->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $article, 'routePrefix' => 'articles'])
        </div>
    @endif

    <form method="POST" action="{{ $article->exists ? route('admin.articles.update', $article) : route('admin.articles.store') }}">
        @csrf
        @if ($article->exists) @method('PUT') @endif

        <div class="mb-4 flex justify-end">
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
        </div>

        <div class="max-w-3xl rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <label for="title" class="mt-4 block text-sm font-medium text-charcoal-700">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="slug" class="mt-4 block text-sm font-medium text-charcoal-700">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $article->slug) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="excerpt" class="mt-4 block text-sm font-medium text-charcoal-700">Excerpt</label>
            <input type="text" name="excerpt" id="excerpt" value="{{ old('excerpt', $article->excerpt) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="body" class="mt-4 block text-sm font-medium text-charcoal-700">Body</label>
            <textarea name="body" id="body" rows="4" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('body', $article->body) }}</textarea>
            <label for="category" class="mt-4 block text-sm font-medium text-charcoal-700">Category</label>
            <input type="text" name="category" id="category" value="{{ old('category', $article->category) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="reading_time_minutes" class="mt-4 block text-sm font-medium text-charcoal-700">Reading Time Minutes</label>
            <input type="number" name="reading_time_minutes" id="reading_time_minutes" value="{{ old('reading_time_minutes', $article->reading_time_minutes) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">

            @include('admin.partials.media-picker', [
                'name' => 'featured_image_id',
                'value' => $article->featured_image_id,
                'label' => 'Article image',
            ])
        </div>
    </form>
@endsection