@extends('layouts.admin')

@section('title', $webinar->exists ? 'Edit Webinar' : 'New Webinar')
@section('heading', $webinar->exists ? 'Edit Webinar' : 'New Webinar')

@section('content')
    @if ($webinar->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $webinar, 'routePrefix' => 'webinars'])
        </div>
    @endif

    <form method="POST" action="{{ $webinar->exists ? route('admin.webinars.update', $webinar) : route('admin.webinars.store') }}">
        @csrf
        @if ($webinar->exists) @method('PUT') @endif

        <div class="mb-4 flex justify-end">
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
        </div>

        <div class="max-w-3xl space-y-4 rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <div>
                <label for="title" class="block text-sm font-medium text-charcoal-700">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $webinar->title) }}" required class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            </div>
            <div>
                <label for="slug" class="block text-sm font-medium text-charcoal-700">Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $webinar->slug) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            </div>
            <div>
                <label for="summary" class="block text-sm font-medium text-charcoal-700">Summary</label>
                <textarea name="summary" id="summary" rows="3" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('summary', $webinar->summary) }}</textarea>
            </div>
            <div>
                <label for="body" class="block text-sm font-medium text-charcoal-700">Body</label>
                <textarea name="body" id="body" rows="6" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('body', $webinar->body) }}</textarea>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="held_at" class="block text-sm font-medium text-charcoal-700">Held at</label>
                    <input type="datetime-local" name="held_at" id="held_at" value="{{ old('held_at', optional($webinar->held_at)->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
                <div>
                    <label for="participant_count" class="block text-sm font-medium text-charcoal-700">Participant count</label>
                    <input type="number" name="participant_count" id="participant_count" min="0" value="{{ old('participant_count', $webinar->participant_count) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
            </div>
            <div>
                <label for="moderator" class="block text-sm font-medium text-charcoal-700">Moderator</label>
                <input type="text" name="moderator" id="moderator" value="{{ old('moderator', $webinar->moderator) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            </div>
            <div>
                <label for="recording_url" class="block text-sm font-medium text-charcoal-700">Recording URL</label>
                <input type="text" name="recording_url" id="recording_url" value="{{ old('recording_url', $webinar->recording_url) }}" placeholder="https://… or /resources/webinars" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            </div>
            <div>
                <label for="transcript" class="block text-sm font-medium text-charcoal-700">Transcript</label>
                <textarea name="transcript" id="transcript" rows="5" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('transcript', $webinar->transcript) }}</textarea>
            </div>

            @include('admin.partials.media-picker', [
                'name' => 'featured_image_id',
                'value' => $webinar->featured_image_id,
                'label' => 'Webinar image',
            ])
        </div>
    </form>
@endsection
