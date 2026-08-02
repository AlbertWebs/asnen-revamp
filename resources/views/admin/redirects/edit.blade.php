@extends('layouts.admin')

@section('title', $redirect->exists ? 'Edit' : 'New')
@section('heading', $redirect->exists ? 'Edit' : 'New')

@section('content')
    <form method="POST" action="{{ $redirect->exists ? route('admin.redirects.update', $redirect) : route('admin.redirects.store') }}">
        @csrf
        @if ($redirect->exists) @method('PUT') @endif

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
        </div>

        <div class="max-w-3xl rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <label for="from_path" class="mt-4 block text-sm font-medium text-charcoal-700">From Path</label>
            <input type="text" name="from_path" id="from_path" value="{{ old('from_path', $redirect->from_path) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="to_path" class="mt-4 block text-sm font-medium text-charcoal-700">To Path</label>
            <input type="text" name="to_path" id="to_path" value="{{ old('to_path', $redirect->to_path) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="status_code" class="mt-4 block text-sm font-medium text-charcoal-700">Status Code</label>
            <input type="number" name="status_code" id="status_code" value="{{ old('status_code', $redirect->status_code) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
        </div>
    </form>
@endsection