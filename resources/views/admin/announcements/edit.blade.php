@extends('layouts.admin')

@section('title', $announcement->exists ? 'Edit Announcement' : 'New Announcement')
@section('heading', $announcement->exists ? 'Edit Announcement' : 'New Announcement')

@section('content')
    <form method="POST" action="{{ $announcement->exists ? route('admin.announcements.update', $announcement) : route('admin.announcements.store') }}">
        @csrf
        @if ($announcement->exists) @method('PUT') @endif

        <div class="mb-4 flex justify-end">
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
        </div>

        <div class="max-w-3xl space-y-4 rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <div>
                <label for="message" class="block text-sm font-medium text-charcoal-700">Message</label>
                <textarea name="message" id="message" rows="4" required class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('message', $announcement->message) }}</textarea>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="link_url" class="block text-sm font-medium text-charcoal-700">Link URL</label>
                    <input type="text" name="link_url" id="link_url" value="{{ old('link_url', $announcement->link_url) }}" placeholder="/contact or https://…" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
                <div>
                    <label for="link_label" class="block text-sm font-medium text-charcoal-700">Link label</label>
                    <input type="text" name="link_label" id="link_label" value="{{ old('link_label', $announcement->link_label) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="starts_at" class="block text-sm font-medium text-charcoal-700">Starts at</label>
                    <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at', optional($announcement->starts_at)->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
                <div>
                    <label for="ends_at" class="block text-sm font-medium text-charcoal-700">Ends at</label>
                    <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at', optional($announcement->ends_at)->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-charcoal-300 text-forest-700 focus:ring-forest-500" @checked(old('is_active', $announcement->is_active ?? true))>
                <label for="is_active" class="text-sm text-charcoal-700">Active on the public site</label>
            </div>
        </div>
    </form>
@endsection
