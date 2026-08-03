@extends('layouts.admin')

@section('title', $publication->exists ? 'Edit Publication' : 'New Publication')
@section('heading', $publication->exists ? 'Edit Publication' : 'New Publication')

@section('content')
    @if ($publication->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $publication, 'routePrefix' => 'publications'])
        </div>
    @endif

    <form method="POST" action="{{ $publication->exists ? route('admin.publications.update', $publication) : route('admin.publications.store') }}" class="admin-form admin-form--wide">
        @csrf
        @if ($publication->exists) @method('PUT') @endif

        <div class="admin-form__body">
            <header class="admin-form__header">
                <p class="admin-form__eyebrow">Publications</p>
                <h2 class="admin-form__title">{{ $publication->exists ? 'Edit publication' : 'New publication' }}</h2>
            </header>

            <div class="admin-form__section">
                <div class="admin-field">
                    <label for="title" class="admin-label">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $publication->title) }}" class="admin-input">
                </div>

                <div class="admin-field">
                    <label for="slug" class="admin-label">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $publication->slug) }}" class="admin-input">
                </div>

                <div class="admin-field">
                    <label for="category" class="admin-label">Category</label>
                    <select name="category" id="category" class="admin-select">
                        @foreach ([
                            'annual_report' => 'Annual report',
                            'conference_report' => 'Conference report',
                            'impact_report' => 'Impact report',
                            'report' => 'Report',
                            'toolkit' => 'Toolkit',
                            'other' => 'Other',
                        ] as $value => $label)
                            <option value="{{ $value }}" @selected(old('category', $publication->category) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="admin-field">
                    <label for="year" class="admin-label">Year</label>
                    <input type="number" name="year" id="year" value="{{ old('year', $publication->year) }}" class="admin-input">
                </div>

                <div class="admin-field">
                    <label for="abstract" class="admin-label">Abstract</label>
                    <textarea name="abstract" id="abstract" rows="4" class="admin-textarea">{{ old('abstract', $publication->abstract) }}</textarea>
                </div>

                <div class="admin-field">
                    <label for="version" class="admin-label">Version</label>
                    <input type="text" name="version" id="version" value="{{ old('version', $publication->version) }}" class="admin-input">
                </div>

                @include('admin.partials.media-picker', [
                    'name' => 'cover_id',
                    'value' => $publication->cover_id,
                    'label' => 'Cover image',
                    'type' => 'image',
                ])

                @include('admin.partials.media-picker', [
                    'name' => 'file_id',
                    'value' => $publication->file_id,
                    'label' => 'Downloadable file (PDF)',
                    'type' => 'file',
                    'help' => 'This is the file visitors download from Impact Reports and Publications.',
                ])

                @include('admin.partials.media-picker', [
                    'name' => 'accessible_file_id',
                    'value' => $publication->accessible_file_id,
                    'label' => 'Accessible alternative (optional)',
                    'type' => 'file',
                    'help' => 'Optional accessible or large-print version.',
                ])
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn-primary">Save</button>
            </div>
        </div>
    </form>
@endsection
