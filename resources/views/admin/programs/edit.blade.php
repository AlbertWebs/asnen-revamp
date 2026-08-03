@extends('layouts.admin')

@section('title', $program->exists ? 'Edit' : 'New')
@section('heading', $program->exists ? 'Edit' : 'New')

@section('content')
    @if ($program->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $program, 'routePrefix' => 'programs'])
        </div>
    @endif

    <form method="POST" action="{{ $program->exists ? route('admin.programs.update', $program) : route('admin.programs.store') }}">
        @csrf
        @if ($program->exists) @method('PUT') @endif

        <div class="mb-4 flex justify-end">
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
        </div>

        <div class="max-w-3xl rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <label for="title" class="mt-4 block text-sm font-medium text-charcoal-700">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $program->title) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="slug" class="mt-4 block text-sm font-medium text-charcoal-700">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $program->slug) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="summary" class="mt-4 block text-sm font-medium text-charcoal-700">Summary</label>
            <textarea name="summary" id="summary" rows="4" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('summary', $program->summary) }}</textarea>
            <label for="body" class="mt-4 block text-sm font-medium text-charcoal-700">Body</label>
            <textarea name="body" id="body" rows="4" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('body', $program->body) }}</textarea>
            <label for="icon" class="mt-4 block text-sm font-medium text-charcoal-700">Icon</label>
            <input type="text" name="icon" id="icon" value="{{ old('icon', $program->icon) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="sort_order" class="mt-4 block text-sm font-medium text-charcoal-700">Sort Order</label>
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $program->sort_order) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">

            @include('admin.partials.media-picker', [
                'name' => 'featured_image_id',
                'value' => $program->featured_image_id,
                'label' => 'Program image',
                'help' => 'Shown on the homepage programs list and program pages.',
            ])
        </div>
    </form>
@endsection