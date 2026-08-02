@extends('layouts.admin')

@section('title', $announcement->exists ? 'Edit' : 'New')
@section('heading', $announcement->exists ? 'Edit' : 'New')

@section('content')
    <form method="POST" action="{{ $announcement->exists ? route('admin.announcements.update', $announcement) : route('admin.announcements.store') }}">
        @csrf
        @if ($announcement->exists) @method('PUT') @endif

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
        </div>

        <div class="max-w-3xl rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <label for="message" class="mt-4 block text-sm font-medium text-charcoal-700">Message</label>
            <textarea name="message" id="message" rows="4" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('message', $announcement->message) }}</textarea>
            <label for="link_url" class="mt-4 block text-sm font-medium text-charcoal-700">Link Url</label>
            <input type="text" name="link_url" id="link_url" value="{{ old('link_url', $announcement->link_url) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="link_label" class="mt-4 block text-sm font-medium text-charcoal-700">Link Label</label>
            <input type="text" name="link_label" id="link_label" value="{{ old('link_label', $announcement->link_label) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="starts_at" class="mt-4 block text-sm font-medium text-charcoal-700">Starts At</label>
            <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at', $announcement->starts_at) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="ends_at" class="mt-4 block text-sm font-medium text-charcoal-700">Ends At</label>
            <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at', $announcement->ends_at) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
        </div>
    </form>
@endsection