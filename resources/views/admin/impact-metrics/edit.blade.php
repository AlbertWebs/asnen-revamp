@extends('layouts.admin')

@section('title', $metric->exists ? 'Edit' : 'New')
@section('heading', $metric->exists ? 'Edit' : 'New')

@section('content')
    <form method="POST" action="{{ $metric->exists ? route('admin.impact-metrics.update', $metric) : route('admin.impact-metrics.store') }}">
        @csrf
        @if ($metric->exists) @method('PUT') @endif

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            @include('admin.partials.publish-buttons', ['model' => $metric, 'routePrefix' => 'impact-metrics'])
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
        </div>

        <div class="max-w-3xl rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <label for="label" class="mt-4 block text-sm font-medium text-charcoal-700">Label</label>
            <input type="text" name="label" id="label" value="{{ old('label', $metric->label) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="value" class="mt-4 block text-sm font-medium text-charcoal-700">Value</label>
            <input type="text" name="value" id="value" value="{{ old('value', $metric->value) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="numeric_value" class="mt-4 block text-sm font-medium text-charcoal-700">Numeric Value</label>
            <input type="number" name="numeric_value" id="numeric_value" value="{{ old('numeric_value', $metric->numeric_value) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="unit" class="mt-4 block text-sm font-medium text-charcoal-700">Unit</label>
            <input type="text" name="unit" id="unit" value="{{ old('unit', $metric->unit) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="region" class="mt-4 block text-sm font-medium text-charcoal-700">Region</label>
            <input type="text" name="region" id="region" value="{{ old('region', $metric->region) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="public_label" class="mt-4 block text-sm font-medium text-charcoal-700">Public Label</label>
            <input type="text" name="public_label" id="public_label" value="{{ old('public_label', $metric->public_label) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
        </div>
    </form>
@endsection