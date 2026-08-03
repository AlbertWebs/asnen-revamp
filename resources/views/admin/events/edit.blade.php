@extends('layouts.admin')

@section('title', $event->exists ? 'Edit Event' : 'New Event')
@section('heading', $event->exists ? 'Edit Event' : 'New Event')

@section('content')
    @if ($event->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $event, 'routePrefix' => 'events'])
        </div>
    @endif

    <form method="POST" action="{{ $event->exists ? route('admin.events.update', $event) : route('admin.events.store') }}" class="admin-form admin-form--wide">
        @csrf
        @if ($event->exists) @method('PUT') @endif

        <div class="admin-form__body">
            <header class="admin-form__header">
                <p class="admin-form__eyebrow">Events</p>
                <h2 class="admin-form__title">{{ $event->exists ? 'Edit event' : 'New event' }}</h2>
            </header>

            <div class="admin-form__section">
                <div class="admin-field">
                    <label for="title" class="admin-label">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" required class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="slug" class="admin-label">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $event->slug) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="type" class="admin-label">Type</label>
                    <select name="type" id="type" class="admin-select">
                        @php $type = old('type', $event->type); @endphp
                        <option value="">Select type</option>
                        @foreach (['conference' => 'Conference', 'workshop' => 'Workshop', 'webinar' => 'Webinar', 'outreach' => 'Outreach', 'other' => 'Other'] as $value => $label)
                            <option value="{{ $value }}" @selected($type === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="admin-field">
                    <label for="summary" class="admin-label">Summary</label>
                    <textarea name="summary" id="summary" rows="3" class="admin-textarea">{{ old('summary', $event->summary) }}</textarea>
                </div>
                <div class="admin-field">
                    <label for="body" class="admin-label">Body</label>
                    <textarea name="body" id="body" rows="6" class="admin-textarea">{{ old('body', $event->body) }}</textarea>
                </div>
                <div class="admin-field">
                    <label for="venue" class="admin-label">Venue</label>
                    <input type="text" name="venue" id="venue" value="{{ old('venue', $event->venue) }}" class="admin-input">
                </div>
                <label class="admin-check">
                    <input type="checkbox" name="is_online" id="is_online" value="1" @checked(old('is_online', $event->is_online))>
                    <span>Online event</span>
                </label>
                <div class="admin-field">
                    <label for="online_url" class="admin-label">Online URL</label>
                    <input type="text" name="online_url" id="online_url" value="{{ old('online_url', $event->online_url) }}" placeholder="https://… or /events-learning/…" class="admin-input">
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="admin-field">
                        <label for="starts_at" class="admin-label">Starts at</label>
                        <input type="datetime-local" name="starts_at" id="starts_at" required value="{{ old('starts_at', optional($event->starts_at)->format('Y-m-d\TH:i')) }}" class="admin-input">
                    </div>
                    <div class="admin-field">
                        <label for="ends_at" class="admin-label">Ends at</label>
                        <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at', optional($event->ends_at)->format('Y-m-d\TH:i')) }}" class="admin-input">
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="admin-field">
                        <label for="timezone" class="admin-label">Timezone</label>
                        <input type="text" name="timezone" id="timezone" value="{{ old('timezone', $event->timezone ?: 'Africa/Nairobi') }}" class="admin-input">
                    </div>
                    <div class="admin-field">
                        <label for="capacity" class="admin-label">Capacity</label>
                        <input type="number" name="capacity" id="capacity" min="1" value="{{ old('capacity', $event->capacity) }}" class="admin-input">
                    </div>
                </div>

                @include('admin.partials.media-picker', [
                    'name' => 'featured_image_id',
                    'value' => $event->featured_image_id,
                    'label' => 'Event image',
                    'help' => 'Card and event detail hero image.',
                ])
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn-primary">Save</button>
            </div>
        </div>
    </form>
@endsection
