@extends('layouts.admin')

@section('title', $redirect->exists ? 'Edit Redirect' : 'New Redirect')
@section('heading', $redirect->exists ? 'Edit Redirect' : 'New Redirect')

@section('content')
    <form method="POST" action="{{ $redirect->exists ? route('admin.redirects.update', $redirect) : route('admin.redirects.store') }}">
        @csrf
        @if ($redirect->exists) @method('PUT') @endif

        <div class="mb-4 flex justify-end">
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
        </div>

        <div class="max-w-3xl space-y-4 rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <div>
                <label for="from_path" class="block text-sm font-medium text-charcoal-700">From path</label>
                <input type="text" name="from_path" id="from_path" value="{{ old('from_path', $redirect->from_path) }}" required placeholder="/old-page.html" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            </div>
            <div>
                <label for="to_path" class="block text-sm font-medium text-charcoal-700">To path</label>
                <input type="text" name="to_path" id="to_path" value="{{ old('to_path', $redirect->to_path) }}" required placeholder="/new-page" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            </div>
            <div>
                <label for="status_code" class="block text-sm font-medium text-charcoal-700">Status code</label>
                <select name="status_code" id="status_code" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                    @foreach ([301, 302, 307, 308] as $code)
                        <option value="{{ $code }}" @selected((int) old('status_code', $redirect->status_code ?: 301) === $code)>{{ $code }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-charcoal-300 text-forest-700 focus:ring-forest-500" @checked(old('is_active', $redirect->is_active ?? true))>
                <label for="is_active" class="text-sm text-charcoal-700">Active</label>
            </div>
        </div>
    </form>
@endsection
