@extends('layouts.admin')

@section('title', 'Submission #'.$formSubmission->id)
@section('heading', 'Submission #'.$formSubmission->id)

@section('content')
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="admin-form admin-form--full">
            <div class="admin-form__body">
                <h2 class="admin-form__section-title">Submission data</h2>
                <dl class="space-y-3 text-sm">
                    <div><dt class="font-semibold text-charcoal">Form</dt><dd class="text-charcoal/70">{{ $formSubmission->formDefinition?->name }}</dd></div>
                    <div><dt class="font-semibold text-charcoal">Submitted</dt><dd class="text-charcoal/70">{{ $formSubmission->created_at?->toDayDateTimeString() }}</dd></div>
                    @foreach ($formSubmission->data ?? [] as $key => $value)
                        <div>
                            <dt class="font-semibold text-charcoal">{{ e($key) }}</dt>
                            <dd class="whitespace-pre-wrap text-charcoal/70">{{ is_array($value) ? e(json_encode($value)) : e((string) $value) }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </div>

        @can('form_submissions.update')
            <form method="POST" action="{{ route('admin.form-submissions.update', $formSubmission) }}" class="admin-form admin-form--full">
                @csrf
                @method('PUT')

                <div class="admin-form__body">
                    <h2 class="admin-form__section-title">Manage</h2>

                    <div class="admin-field">
                        <label for="status" class="admin-label">Status</label>
                        <select name="status" id="status" class="admin-select">
                            @foreach (\App\Enums\FormSubmissionStatus::cases() as $status)
                                <option value="{{ $status->value }}" @selected(old('status', $formSubmission->status?->value ?? $formSubmission->status) === $status->value)>{{ ucfirst(str_replace('_', ' ', $status->value)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="admin-field">
                        <label for="admin_notes" class="admin-label">Admin notes</label>
                        <textarea name="admin_notes" id="admin_notes" rows="4" class="admin-textarea">{{ old('admin_notes', $formSubmission->admin_notes) }}</textarea>
                    </div>

                    <div class="admin-form__actions">
                        <button type="submit" class="admin-btn-primary">Update</button>
                    </div>
                </div>
            </form>
        @endcan
    </div>
@endsection
