@extends('layouts.admin')

@section('title', $event->exists ? 'Edit' : 'New')
@section('heading', $event->exists ? 'Edit' : 'New')

@section('content')
    <form method="POST" action="{{ $event->exists ? route('admin.events.update', $event) : route('admin.events.store') }}">
        @csrf
        @if ($event->exists) @method('PUT') @endif

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            @include('admin.partials.publish-buttons', ['model' => $event, 'routePrefix' => 'events'])
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
        </div>

        <div class="max-w-3xl rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <label for="title" class="mt-4 block text-sm font-medium text-charcoal-700">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="slug" class="mt-4 block text-sm font-medium text-charcoal-700">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $event->slug) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="summary" class="mt-4 block text-sm font-medium text-charcoal-700">Summary</label>
            <textarea name="summary" id="summary" rows="4" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('summary', $event->summary) }}</textarea>
            <label for="body" class="mt-4 block text-sm font-medium text-charcoal-700">Body</label>
            <textarea name="body" id="body" rows="4" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('body', $event->body) }}</textarea>
            <label for="venue" class="mt-4 block text-sm font-medium text-charcoal-700">Venue</label>
            <input type="text" name="venue" id="venue" value="{{ old('venue', $event->venue) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="starts_at" class="mt-4 block text-sm font-medium text-charcoal-700">Starts At</label>
            <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at', $event->starts_at) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="ends_at" class="mt-4 block text-sm font-medium text-charcoal-700">Ends At</label>
            <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at', $event->ends_at) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="timezone" class="mt-4 block text-sm font-medium text-charcoal-700">Timezone</label>
            <input type="text" name="timezone" id="timezone" value="{{ old('timezone', $event->timezone) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="capacity" class="mt-4 block text-sm font-medium text-charcoal-700">Capacity</label>
            <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $event->capacity) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">

            @include('admin.partials.media-picker', [
                'name' => 'featured_image_id',
                'value' => $event->featured_image_id,
                'label' => 'Event image',
                'help' => 'Card and event detail hero image.',
            ])
        </div>
    </form>
@endsection