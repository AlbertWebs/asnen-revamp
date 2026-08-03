@extends('layouts.admin')

@section('title', $metric->exists ? 'Edit Impact Metric' : 'New Impact Metric')
@section('heading', $metric->exists ? 'Edit Impact Metric' : 'New Impact Metric')

@section('content')
    @if ($metric->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $metric, 'routePrefix' => 'impact-metrics'])
        </div>
    @endif

    <form method="POST" action="{{ $metric->exists ? route('admin.impact-metrics.update', $metric) : route('admin.impact-metrics.store') }}" class="admin-form admin-form--wide">
        @csrf
        @if ($metric->exists) @method('PUT') @endif

        <div class="admin-form__body">
            <header class="admin-form__header">
                <p class="admin-form__eyebrow">Impact metrics</p>
                <h2 class="admin-form__title">{{ $metric->exists ? 'Edit impact metric' : 'New impact metric' }}</h2>
            </header>

            <div class="admin-form__section">
                <div class="admin-field">
                    <label for="label" class="admin-label">Label</label>
                    <input type="text" name="label" id="label" value="{{ old('label', $metric->label) }}" required class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="public_label" class="admin-label">Public label</label>
                    <input type="text" name="public_label" id="public_label" value="{{ old('public_label', $metric->public_label) }}" class="admin-input">
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="admin-field">
                        <label for="value" class="admin-label">Display value</label>
                        <input type="text" name="value" id="value" value="{{ old('value', $metric->value) }}" required class="admin-input">
                    </div>
                    <div class="admin-field">
                        <label for="numeric_value" class="admin-label">Numeric value</label>
                        <input type="number" step="any" name="numeric_value" id="numeric_value" value="{{ old('numeric_value', $metric->numeric_value) }}" class="admin-input">
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="admin-field">
                        <label for="unit" class="admin-label">Unit</label>
                        <input type="text" name="unit" id="unit" value="{{ old('unit', $metric->unit) }}" class="admin-input">
                    </div>
                    <div class="admin-field">
                        <label for="qualifier" class="admin-label">Qualifier</label>
                        <input type="text" name="qualifier" id="qualifier" value="{{ old('qualifier', $metric->qualifier) }}" placeholder="e.g. since 2023" class="admin-input">
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="admin-field">
                        <label for="region" class="admin-label">Region</label>
                        <input type="text" name="region" id="region" value="{{ old('region', $metric->region) }}" class="admin-input">
                    </div>
                    <div class="admin-field">
                        <label for="as_of_date" class="admin-label">As of date</label>
                        <input type="date" name="as_of_date" id="as_of_date" value="{{ old('as_of_date', optional($metric->as_of_date)->format('Y-m-d')) }}" class="admin-input">
                    </div>
                </div>
                <div class="admin-field">
                    <label for="source_label" class="admin-label">Source label</label>
                    <input type="text" name="source_label" id="source_label" value="{{ old('source_label', $metric->source_label) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="program_id" class="admin-label">Programme</label>
                    <select name="program_id" id="program_id" class="admin-select">
                        <option value="">None</option>
                        @foreach (($programs ?? []) as $program)
                            <option value="{{ $program->id }}" @selected((string) old('program_id', $metric->program_id) === (string) $program->id)>{{ $program->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn-primary">Save</button>
            </div>
        </div>
    </form>
@endsection
