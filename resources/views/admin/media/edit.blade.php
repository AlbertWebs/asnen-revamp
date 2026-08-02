@extends('layouts.admin')

@section('title', 'Edit Media')
@section('heading', 'Edit Media')

@section('content')
    <form method="POST" action="{{ route('admin.media.update', $asset) }}" class="max-w-xl rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
        @csrf
        @method('PUT')

        <p class="text-sm text-charcoal-600">{{ $asset->filename }} ({{ number_format($asset->size / 1024, 1) }} KB)</p>

        <label for="alt" class="mt-4 block text-sm font-medium text-charcoal-700">Alt text</label>
        <input type="text" name="alt" id="alt" value="{{ old('alt', $asset->alt) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">

        <label for="caption" class="mt-4 block text-sm font-medium text-charcoal-700">Caption</label>
        <textarea name="caption" id="caption" rows="3" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('caption', $asset->caption) }}</textarea>

        <label for="consent_status" class="mt-4 block text-sm font-medium text-charcoal-700">Consent status</label>
        <select name="consent_status" id="consent_status" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            @foreach (\App\Enums\ConsentStatus::cases() as $status)
                <option value="{{ $status->value }}" @selected(old('consent_status', $asset->consent_status?->value ?? $asset->consent_status) === $status->value)>{{ ucfirst(str_replace('_', ' ', $status->value)) }}</option>
            @endforeach
        </select>

        <label for="consent_notes" class="mt-4 block text-sm font-medium text-charcoal-700">Consent notes</label>
        <textarea name="consent_notes" id="consent_notes" rows="2" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('consent_notes', $asset->consent_notes) }}</textarea>

        <label for="credit" class="mt-4 block text-sm font-medium text-charcoal-700">Credit</label>
        <input type="text" name="credit" id="credit" value="{{ old('credit', $asset->credit) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">

        <label class="mt-4 flex items-center gap-2 text-sm text-charcoal-700">
            <input type="checkbox" name="is_private" value="1" @checked(old('is_private', $asset->is_private)) class="rounded border-charcoal-300 text-forest-600 focus:ring-forest-500">
            Private asset
        </label>

        <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
    </form>

    @can('media.delete')
        <form method="POST" action="{{ route('admin.media.destroy', $asset) }}" class="mt-4" onsubmit="return confirm('Delete this media asset?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-md border border-red-300 px-4 py-2 text-sm text-red-700 hover:bg-red-50">Delete</button>
        </form>
    @endcan
@endsection
