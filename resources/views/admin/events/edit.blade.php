@extends('layouts.admin')

@section('title', $event->exists ? 'Edit Event' : 'New Event')
@section('heading', $event->exists ? 'Edit Event' : 'New Event')

@section('content')
    @if ($event->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $event, 'routePrefix' => 'events'])
        </div>
    @endif

    <form method="POST" action="{{ $event->exists ? route('admin.events.update', $event) : route('admin.events.store') }}">
        @csrf
        @if ($event->exists) @method('PUT') @endif

        <div class="mb-4 flex justify-end">
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
        </div>

        <div class="max-w-3xl space-y-4 rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <div>
                <label for="title" class="block text-sm font-medium text-charcoal-700">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" required class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            </div>
            <div>
                <label for="slug" class="block text-sm font-medium text-charcoal-700">Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $event->slug) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            </div>
            <div>
                <label for="type" class="block text-sm font-medium text-charcoal-700">Type</label>
                <select name="type" id="type" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                    @php $type = old('type', $event->type); @endphp
                    <option value="">Select type</option>
                    @foreach (['conference' => 'Conference', 'workshop' => 'Workshop', 'webinar' => 'Webinar', 'outreach' => 'Outreach', 'other' => 'Other'] as $value => $label)
                        <option value="{{ $value }}" @selected($type === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="summary" class="block text-sm font-medium text-charcoal-700">Summary</label>
                <textarea name="summary" id="summary" rows="3" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('summary', $event->summary) }}</textarea>
            </div>
            <div>
                <label for="body" class="block text-sm font-medium text-charcoal-700">Body</label>
                <textarea name="body" id="body" rows="6" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('body', $event->body) }}</textarea>
            </div>
            <div>
                <label for="venue" class="block text-sm font-medium text-charcoal-700">Venue</label>
                <input type="text" name="venue" id="venue" value="{{ old('venue', $event->venue) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_online" id="is_online" value="1" class="rounded border-charcoal-300 text-forest-700 focus:ring-forest-500" @checked(old('is_online', $event->is_online))>
                <label for="is_online" class="text-sm text-charcoal-700">Online event</label>
            </div>
            <div>
                <label for="online_url" class="block text-sm font-medium text-charcoal-700">Online URL</label>
                <input type="text" name="online_url" id="online_url" value="{{ old('online_url', $event->online_url) }}" placeholder="https://… or /events-learning/…" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="starts_at" class="block text-sm font-medium text-charcoal-700">Starts at</label>
                    <input type="datetime-local" name="starts_at" id="starts_at" required value="{{ old('starts_at', optional($event->starts_at)->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
                <div>
                    <label for="ends_at" class="block text-sm font-medium text-charcoal-700">Ends at</label>
                    <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at', optional($event->ends_at)->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="timezone" class="block text-sm font-medium text-charcoal-700">Timezone</label>
                    <input type="text" name="timezone" id="timezone" value="{{ old('timezone', $event->timezone ?: 'Africa/Nairobi') }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
                <div>
                    <label for="capacity" class="block text-sm font-medium text-charcoal-700">Capacity</label>
                    <input type="number" name="capacity" id="capacity" min="1" value="{{ old('capacity', $event->capacity) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
            </div>

            @include('admin.partials.media-picker', [
                'name' => 'featured_image_id',
                'value' => $event->featured_image_id,
                'label' => 'Event image',
                'help' => 'Card and event detail hero image.',
            ])
        </div>
    </form>
@endsection
