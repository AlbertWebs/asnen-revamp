@extends('layouts.admin')

@section('title', $partner->exists ? 'Edit' : 'New')
@section('heading', $partner->exists ? 'Edit' : 'New')

@section('content')
    <form method="POST" action="{{ $partner->exists ? route('admin.partners.update', $partner) : route('admin.partners.store') }}">
        @csrf
        @if ($partner->exists) @method('PUT') @endif

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            @include('admin.partials.publish-buttons', ['model' => $partner, 'routePrefix' => 'partners'])
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
        </div>

        <div class="max-w-3xl rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <label for="name" class="mt-4 block text-sm font-medium text-charcoal-700">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $partner->name) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="slug" class="mt-4 block text-sm font-medium text-charcoal-700">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $partner->slug) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="description" class="mt-4 block text-sm font-medium text-charcoal-700">Description</label>
            <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('description', $partner->description) }}</textarea>
            <label for="url" class="mt-4 block text-sm font-medium text-charcoal-700">Url</label>
            <input type="text" name="url" id="url" value="{{ old('url', $partner->url) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="category" class="mt-4 block text-sm font-medium text-charcoal-700">Category</label>
            <input type="text" name="category" id="category" value="{{ old('category', $partner->category) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="sort_order" class="mt-4 block text-sm font-medium text-charcoal-700">Sort Order</label>
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $partner->sort_order) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">

            @include('admin.partials.media-picker', [
                'name' => 'logo_id',
                'value' => $partner->logo_id,
                'label' => 'Partner logo',
                'help' => 'Square or landscape logos work best.',
            ])
        </div>
    </form>
@endsection