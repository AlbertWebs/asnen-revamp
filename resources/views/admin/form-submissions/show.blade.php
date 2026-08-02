@extends('layouts.admin')

@section('title', 'Submission #'.$formSubmission->id)
@section('heading', 'Submission #'.$formSubmission->id)

@section('content')
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <h2 class="text-base font-semibold text-charcoal-900">Submission data</h2>
            <dl class="mt-4 space-y-3 text-sm">
                <div><dt class="font-medium text-charcoal-600">Form</dt><dd>{{ $formSubmission->formDefinition?->name }}</dd></div>
                <div><dt class="font-medium text-charcoal-600">Submitted</dt><dd>{{ $formSubmission->created_at?->toDayDateTimeString() }}</dd></div>
                @foreach ($formSubmission->data ?? [] as $key => $value)
                    <div>
                        <dt class="font-medium text-charcoal-600">{{ e($key) }}</dt>
                        <dd class="whitespace-pre-wrap">{{ is_array($value) ? e(json_encode($value)) : e((string) $value) }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>

        @can('form_submissions.update')
            <form method="POST" action="{{ route('admin.form-submissions.update', $formSubmission) }}" class="rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
                @csrf
                @method('PUT')

                <h2 class="text-base font-semibold text-charcoal-900">Manage</h2>

                <label for="status" class="mt-4 block text-sm font-medium text-charcoal-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                    @foreach (\App\Enums\FormSubmissionStatus::cases() as $status)
                        <option value="{{ $status->value }}" @selected(old('status', $formSubmission->status?->value ?? $formSubmission->status) === $status->value)>{{ ucfirst(str_replace('_', ' ', $status->value)) }}</option>
                    @endforeach
                </select>

                <label for="admin_notes" class="mt-4 block text-sm font-medium text-charcoal-700">Admin notes</label>
                <textarea name="admin_notes" id="admin_notes" rows="4" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('admin_notes', $formSubmission->admin_notes) }}</textarea>

                <button type="submit" class="mt-4 rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Update</button>
            </form>
        @endcan
    </div>
@endsection
