@extends('layouts.admin')

@section('title', $announcement->exists ? 'Edit Announcement' : 'New Announcement')
@section('heading', $announcement->exists ? 'Edit Announcement' : 'New Announcement')

@section('content')
    <form method="POST" action="{{ $announcement->exists ? route('admin.announcements.update', $announcement) : route('admin.announcements.store') }}" class="admin-form admin-form--wide">
        @csrf
        @if ($announcement->exists) @method('PUT') @endif

        <div class="admin-form__body">
            <header class="admin-form__header">
                <p class="admin-form__eyebrow">Announcements</p>
                <h2 class="admin-form__title">{{ $announcement->exists ? 'Edit announcement' : 'New announcement' }}</h2>
            </header>

            <div class="admin-form__section">
                <div class="admin-field">
                    <label for="message" class="admin-label">Message</label>
                    <textarea name="message" id="message" rows="4" required class="admin-textarea">{{ old('message', $announcement->message) }}</textarea>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="admin-field">
                        <label for="link_url" class="admin-label">Link URL</label>
                        <input type="text" name="link_url" id="link_url" value="{{ old('link_url', $announcement->link_url) }}" placeholder="/contact or https://…" class="admin-input">
                    </div>
                    <div class="admin-field">
                        <label for="link_label" class="admin-label">Link label</label>
                        <input type="text" name="link_label" id="link_label" value="{{ old('link_label', $announcement->link_label) }}" class="admin-input">
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="admin-field">
                        <label for="starts_at" class="admin-label">Starts at</label>
                        <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at', optional($announcement->starts_at)->format('Y-m-d\TH:i')) }}" class="admin-input">
                    </div>
                    <div class="admin-field">
                        <label for="ends_at" class="admin-label">Ends at</label>
                        <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at', optional($announcement->ends_at)->format('Y-m-d\TH:i')) }}" class="admin-input">
                    </div>
                </div>

                <label class="admin-check">
                    <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $announcement->is_active ?? true))>
                    <span>Active on the public site</span>
                </label>
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn-primary">Save</button>
            </div>
        </div>
    </form>
@endsection
