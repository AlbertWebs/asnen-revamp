@extends('layouts.admin')

@section('title', $webinar->exists ? 'Edit' : 'New')
@section('heading', $webinar->exists ? 'Edit' : 'New')

@section('content')
    <form method="POST" action="{{ $webinar->exists ? route('admin.webinars.update', $webinar) : route('admin.webinars.store') }}">
        @csrf
        @if ($webinar->exists) @method('PUT') @endif

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            @include('admin.partials.publish-buttons', ['model' => $webinar, 'routePrefix' => 'webinars'])
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
        </div>

        <div class="max-w-3xl rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <label for="title" class="mt-4 block text-sm font-medium text-charcoal-700">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $webinar->title) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="slug" class="mt-4 block text-sm font-medium text-charcoal-700">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $webinar->slug) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="summary" class="mt-4 block text-sm font-medium text-charcoal-700">Summary</label>
            <textarea name="summary" id="summary" rows="4" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('summary', $webinar->summary) }}</textarea>
            <label for="body" class="mt-4 block text-sm font-medium text-charcoal-700">Body</label>
            <textarea name="body" id="body" rows="4" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('body', $webinar->body) }}</textarea>
            <label for="held_at" class="mt-4 block text-sm font-medium text-charcoal-700">Held At</label>
            <input type="datetime-local" name="held_at" id="held_at" value="{{ old('held_at', $webinar->held_at) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="moderator" class="mt-4 block text-sm font-medium text-charcoal-700">Moderator</label>
            <input type="text" name="moderator" id="moderator" value="{{ old('moderator', $webinar->moderator) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="recording_url" class="mt-4 block text-sm font-medium text-charcoal-700">Recording Url</label>
            <input type="text" name="recording_url" id="recording_url" value="{{ old('recording_url', $webinar->recording_url) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">

            @include('admin.partials.media-picker', [
                'name' => 'featured_image_id',
                'value' => $webinar->featured_image_id,
                'label' => 'Webinar image',
            ])
        </div>
    </form>
@endsection