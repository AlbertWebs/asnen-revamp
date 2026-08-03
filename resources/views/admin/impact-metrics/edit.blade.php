@extends('layouts.admin')

@section('title', $metric->exists ? 'Edit Impact Metric' : 'New Impact Metric')
@section('heading', $metric->exists ? 'Edit Impact Metric' : 'New Impact Metric')

@section('content')
    @if ($metric->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $metric, 'routePrefix' => 'impact-metrics'])
        </div>
    @endif

    <form method="POST" action="{{ $metric->exists ? route('admin.impact-metrics.update', $metric) : route('admin.impact-metrics.store') }}">
        @csrf
        @if ($metric->exists) @method('PUT') @endif

        <div class="mb-4 flex justify-end">
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
        </div>

        <div class="max-w-3xl space-y-4 rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <div>
                <label for="label" class="block text-sm font-medium text-charcoal-700">Label</label>
                <input type="text" name="label" id="label" value="{{ old('label', $metric->label) }}" required class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            </div>
            <div>
                <label for="public_label" class="block text-sm font-medium text-charcoal-700">Public label</label>
                <input type="text" name="public_label" id="public_label" value="{{ old('public_label', $metric->public_label) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="value" class="block text-sm font-medium text-charcoal-700">Display value</label>
                    <input type="text" name="value" id="value" value="{{ old('value', $metric->value) }}" required class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
                <div>
                    <label for="numeric_value" class="block text-sm font-medium text-charcoal-700">Numeric value</label>
                    <input type="number" step="any" name="numeric_value" id="numeric_value" value="{{ old('numeric_value', $metric->numeric_value) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="unit" class="block text-sm font-medium text-charcoal-700">Unit</label>
                    <input type="text" name="unit" id="unit" value="{{ old('unit', $metric->unit) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
                <div>
                    <label for="qualifier" class="block text-sm font-medium text-charcoal-700">Qualifier</label>
                    <input type="text" name="qualifier" id="qualifier" value="{{ old('qualifier', $metric->qualifier) }}" placeholder="e.g. since 2023" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="region" class="block text-sm font-medium text-charcoal-700">Region</label>
                    <input type="text" name="region" id="region" value="{{ old('region', $metric->region) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
                <div>
                    <label for="as_of_date" class="block text-sm font-medium text-charcoal-700">As of date</label>
                    <input type="date" name="as_of_date" id="as_of_date" value="{{ old('as_of_date', optional($metric->as_of_date)->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
            </div>
            <div>
                <label for="source_label" class="block text-sm font-medium text-charcoal-700">Source label</label>
                <input type="text" name="source_label" id="source_label" value="{{ old('source_label', $metric->source_label) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            </div>
            <div>
                <label for="program_id" class="block text-sm font-medium text-charcoal-700">Programme</label>
                <select name="program_id" id="program_id" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                    <option value="">None</option>
                    @foreach (($programs ?? []) as $program)
                        <option value="{{ $program->id }}" @selected((string) old('program_id', $metric->program_id) === (string) $program->id)>{{ $program->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
@endsection
