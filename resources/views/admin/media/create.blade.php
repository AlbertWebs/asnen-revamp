@extends('layouts.admin')

@section('title', 'Upload Media')
@section('heading', 'Upload Media')

@section('content')
    <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="max-w-xl rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
        @csrf
        @if(!empty($returnUrl))
            <input type="hidden" name="return" value="{{ $returnUrl }}">
        @endif

        <p class="mb-4 rounded-md bg-sand px-3 py-2 text-sm text-charcoal-600">
            Upload photos for the homepage hero, programs, stories, events, partners, and team. After upload, attach them on each content edit screen.
        </p>

        <label for="file" class="block text-sm font-medium text-charcoal-700">File</label>
        <input type="file" name="file" id="file" accept="image/*,video/*,application/pdf" required class="mt-1 block w-full text-sm text-charcoal-700">

        <label for="alt" class="mt-4 block text-sm font-medium text-charcoal-700">Alt text</label>
        <input type="text" name="alt" id="alt" value="{{ old('alt') }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">

        <label for="caption" class="mt-4 block text-sm font-medium text-charcoal-700">Caption</label>
        <textarea name="caption" id="caption" rows="3" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('caption') }}</textarea>

        <label for="consent_status" class="mt-4 block text-sm font-medium text-charcoal-700">Consent status</label>
        <select name="consent_status" id="consent_status" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            @foreach (\App\Enums\ConsentStatus::cases() as $status)
                <option value="{{ $status->value }}" @selected(old('consent_status', 'pending') === $status->value)>{{ ucfirst(str_replace('_', ' ', $status->value)) }}</option>
            @endforeach
        </select>

        <label for="folder" class="mt-4 block text-sm font-medium text-charcoal-700">Folder</label>
        <select name="folder" id="folder" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            @foreach (['hero','programs','stories','events','partners','team','resources','gallery','uploads'] as $folder)
                <option value="{{ $folder }}" @selected(old('folder', $defaultFolder ?? 'uploads') === $folder)>{{ $folder }}</option>
            @endforeach
        </select>

        <button type="submit" class="mt-6 rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Upload</button>
    </form>
@endsection
