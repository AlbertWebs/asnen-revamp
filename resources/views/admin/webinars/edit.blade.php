@extends('layouts.admin')

@section('title', $webinar->exists ? 'Edit Webinar' : 'New Webinar')
@section('heading', $webinar->exists ? 'Edit Webinar' : 'New Webinar')

@section('content')
    @if ($webinar->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $webinar, 'routePrefix' => 'webinars'])
        </div>
    @endif

    <form method="POST" action="{{ $webinar->exists ? route('admin.webinars.update', $webinar) : route('admin.webinars.store') }}" class="admin-form admin-form--wide">
        @csrf
        @if ($webinar->exists) @method('PUT') @endif

        <div class="admin-form__body">
            <header class="admin-form__header">
                <p class="admin-form__eyebrow">Webinars</p>
                <h2 class="admin-form__title">{{ $webinar->exists ? 'Edit webinar' : 'New webinar' }}</h2>
            </header>

            <div class="admin-form__section">
                <div class="admin-field">
                    <label for="title" class="admin-label">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $webinar->title) }}" required class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="slug" class="admin-label">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $webinar->slug) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="summary" class="admin-label">Summary</label>
                    <textarea name="summary" id="summary" rows="3" class="admin-textarea">{{ old('summary', $webinar->summary) }}</textarea>
                </div>
                <div class="admin-field">
                    <label for="body" class="admin-label">Body</label>
                    <textarea name="body" id="body" rows="6" class="admin-textarea">{{ old('body', $webinar->body) }}</textarea>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="admin-field">
                        <label for="held_at" class="admin-label">Held at</label>
                        <input type="datetime-local" name="held_at" id="held_at" value="{{ old('held_at', optional($webinar->held_at)->format('Y-m-d\TH:i')) }}" class="admin-input">
                    </div>
                    <div class="admin-field">
                        <label for="participant_count" class="admin-label">Participant count</label>
                        <input type="number" name="participant_count" id="participant_count" min="0" value="{{ old('participant_count', $webinar->participant_count) }}" class="admin-input">
                    </div>
                </div>
                <div class="admin-field">
                    <label for="moderator" class="admin-label">Moderator</label>
                    <input type="text" name="moderator" id="moderator" value="{{ old('moderator', $webinar->moderator) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="recording_url" class="admin-label">Recording URL</label>
                    <input type="text" name="recording_url" id="recording_url" value="{{ old('recording_url', $webinar->recording_url) }}" placeholder="https://… or /resources/webinars" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="transcript" class="admin-label">Transcript</label>
                    <textarea name="transcript" id="transcript" rows="5" class="admin-textarea">{{ old('transcript', $webinar->transcript) }}</textarea>
                </div>

                @include('admin.partials.media-picker', [
                    'name' => 'featured_image_id',
                    'value' => $webinar->featured_image_id,
                    'label' => 'Webinar image',
                ])
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn-primary">Save</button>
            </div>
        </div>
    </form>
@endsection
