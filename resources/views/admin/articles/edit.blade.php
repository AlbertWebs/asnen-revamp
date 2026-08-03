@extends('layouts.admin')

@section('title', $article->exists ? 'Edit' : 'New')
@section('heading', $article->exists ? 'Edit' : 'New')

@section('content')
    @if ($article->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $article, 'routePrefix' => 'articles'])
        </div>
    @endif

    <form method="POST" action="{{ $article->exists ? route('admin.articles.update', $article) : route('admin.articles.store') }}" class="admin-form admin-form--wide">
        @csrf
        @if ($article->exists) @method('PUT') @endif

        <div class="admin-form__body">
            <header class="admin-form__header">
                <p class="admin-form__eyebrow">Articles</p>
                <h2 class="admin-form__title">{{ $article->exists ? 'Edit article' : 'New article' }}</h2>
            </header>

            <div class="admin-form__section">
                <div class="admin-field">
                    <label for="title" class="admin-label">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="slug" class="admin-label">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $article->slug) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="excerpt" class="admin-label">Excerpt</label>
                    <input type="text" name="excerpt" id="excerpt" value="{{ old('excerpt', $article->excerpt) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="body" class="admin-label">Body</label>
                    <textarea name="body" id="body" rows="4" class="admin-textarea">{{ old('body', $article->body) }}</textarea>
                </div>
                <div class="admin-field">
                    <label for="category" class="admin-label">Category</label>
                    <input type="text" name="category" id="category" value="{{ old('category', $article->category) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="reading_time_minutes" class="admin-label">Reading time (minutes)</label>
                    <input type="number" name="reading_time_minutes" id="reading_time_minutes" value="{{ old('reading_time_minutes', $article->reading_time_minutes) }}" class="admin-input">
                </div>

                @include('admin.partials.media-picker', [
                    'name' => 'featured_image_id',
                    'value' => $article->featured_image_id,
                    'label' => 'Article image',
                ])
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn-primary">Save</button>
            </div>
        </div>
    </form>
@endsection
