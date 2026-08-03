@extends('layouts.admin')

@section('title', $publication->exists ? 'Edit Publication' : 'New Publication')
@section('heading', $publication->exists ? 'Edit Publication' : 'New Publication')

@section('content')
    @if ($publication->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $publication, 'routePrefix' => 'publications'])
        </div>
    @endif

    <form method="POST" action="{{ $publication->exists ? route('admin.publications.update', $publication) : route('admin.publications.store') }}">
        @csrf
        @if ($publication->exists) @method('PUT') @endif

        <div class="mb-4 flex justify-end">
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
        </div>

        <div class="max-w-3xl rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <label for="title" class="block text-sm font-medium text-charcoal-700">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $publication->title) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">

            <label for="slug" class="mt-4 block text-sm font-medium text-charcoal-700">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $publication->slug) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">

            <label for="category" class="mt-4 block text-sm font-medium text-charcoal-700">Category</label>
            <select name="category" id="category" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
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

            <label for="year" class="mt-4 block text-sm font-medium text-charcoal-700">Year</label>
            <input type="number" name="year" id="year" value="{{ old('year', $publication->year) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">

            <label for="abstract" class="mt-4 block text-sm font-medium text-charcoal-700">Abstract</label>
            <textarea name="abstract" id="abstract" rows="4" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('abstract', $publication->abstract) }}</textarea>

            <label for="version" class="mt-4 block text-sm font-medium text-charcoal-700">Version</label>
            <input type="text" name="version" id="version" value="{{ old('version', $publication->version) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">

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
    </form>
@endsection
