@extends('layouts.admin')

@section('title', $program->exists ? 'Edit' : 'New')
@section('heading', $program->exists ? 'Edit' : 'New')

@section('content')
    @if ($program->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $program, 'routePrefix' => 'programs'])
        </div>
    @endif

    <form method="POST" action="{{ $program->exists ? route('admin.programs.update', $program) : route('admin.programs.store') }}" class="admin-form admin-form--wide">
        @csrf
        @if ($program->exists) @method('PUT') @endif

        <div class="admin-form__body">
            <header class="admin-form__header">
                <p class="admin-form__eyebrow">Programs</p>
                <h2 class="admin-form__title">{{ $program->exists ? 'Edit program' : 'New program' }}</h2>
            </header>

            <div class="admin-form__section">
                <div class="admin-field">
                    <label for="title" class="admin-label">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $program->title) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="slug" class="admin-label">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $program->slug) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="summary" class="admin-label">Summary</label>
                    <textarea name="summary" id="summary" rows="4" class="admin-textarea">{{ old('summary', $program->summary) }}</textarea>
                </div>
                <div class="admin-field">
                    <label for="body" class="admin-label">Body</label>
                    <textarea name="body" id="body" rows="4" class="admin-textarea">{{ old('body', $program->body) }}</textarea>
                </div>
                <div class="admin-field">
                    <label for="icon" class="admin-label">Icon</label>
                    <input type="text" name="icon" id="icon" value="{{ old('icon', $program->icon) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="sort_order" class="admin-label">Sort order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $program->sort_order) }}" class="admin-input">
                </div>

                @include('admin.partials.media-picker', [
                    'name' => 'featured_image_id',
                    'value' => $program->featured_image_id,
                    'label' => 'Program image',
                    'folder' => 'programs',
                    'help' => 'Upload here or pick an existing image. Shown on the homepage programs list and program pages.',
                ])
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn-primary">Save</button>
            </div>
        </div>
    </form>
@endsection
