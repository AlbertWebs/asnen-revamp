@php
    $status = $formSubmission->status?->value ?? $formSubmission->status;
    $formName = $formSubmission->formDefinition?->name ?? 'Form submission';
    $contactName = $formSubmission->contactName();
    $contactEmail = $formSubmission->contactEmail();
    $contactPhone = $formSubmission->contactPhone();
@endphp

@extends('layouts.admin')

@section('title', $formName.' #'.$formSubmission->id)
@section('heading', $formName)

@section('content')
    <div class="admin-toolbar">
        <div>
            <a href="{{ route('admin.form-submissions.index') }}" class="admin-table__link px-0">All submissions</a>
            <p class="admin-toolbar__copy mt-1">
                #{{ $formSubmission->id }}
                @if($contactName)
                    · {{ $contactName }}
                @endif
                · {{ $formSubmission->created_at?->timezone(config('app.timezone'))->format('j M Y, g:i A') }}
            </p>
        </div>
        <div class="admin-toolbar__actions">
            <span class="admin-badge admin-badge--{{ $status }}">{{ $formSubmission->statusLabel() }}</span>
            @if($contactEmail)
                <a href="mailto:{{ $contactEmail }}" class="admin-btn-secondary">Email</a>
            @endif
            @if($contactPhone)
                <a href="tel:{{ preg_replace('/[^\d+]/', '', $contactPhone) }}" class="admin-btn-secondary">Call</a>
            @endif
        </div>
    </div>

    <div class="admin-submission">
        <section class="admin-form admin-form--full">
            <div class="admin-form__body">
                <header class="admin-form__header">
                    <p class="admin-form__eyebrow">Message</p>
                    <h2 class="admin-form__title">What they sent</h2>
                </header>

                <dl class="admin-kv">
                    @forelse ($formSubmission->displayRows() as $row)
                        <div class="admin-kv__row">
                            <dt>{{ $row['label'] }}</dt>
                            <dd>
                                @if($row['href'])
                                    <a href="{{ $row['href'] }}" class="admin-kv__link">{{ $row['value'] }}</a>
                                @else
                                    {{ $row['value'] }}
                                @endif
                            </dd>
                        </div>
                    @empty
                        <div class="admin-kv__row">
                            <dt>Details</dt>
                            <dd>No fields were stored with this submission.</dd>
                        </div>
                    @endforelse
                </dl>
            </div>
        </section>

        <aside class="admin-submission__side">
            @can('form_submissions.update')
                <form method="POST" action="{{ route('admin.form-submissions.update', $formSubmission) }}" class="admin-form admin-form--full">
                    @csrf
                    @method('PUT')
                    <div class="admin-form__body">
                        <header class="admin-form__header">
                            <p class="admin-form__eyebrow">Follow up</p>
                            <h2 class="admin-form__title">Manage</h2>
                        </header>

                        <div class="admin-field">
                            <label for="status" class="admin-label">Status</label>
                            <select name="status" id="status" class="admin-select">
                                @foreach (\App\Enums\FormSubmissionStatus::cases() as $option)
                                    <option value="{{ $option->value }}" @selected(old('status', $status) === $option->value)>
                                        {{ ucfirst(str_replace('_', ' ', $option->value)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="admin-field">
                            <label for="assigned_to" class="admin-label">Assigned to</label>
                            <select name="assigned_to" id="assigned_to" class="admin-select">
                                <option value="">Unassigned</option>
                                @foreach ($assignees ?? [] as $user)
                                    <option value="{{ $user->id }}" @selected((string) old('assigned_to', $formSubmission->assigned_to) === (string) $user->id)>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="admin-field">
                            <label for="admin_notes" class="admin-label">Internal notes</label>
                            <textarea name="admin_notes" id="admin_notes" rows="6" class="admin-textarea" placeholder="Next step, who replied, or anything the team should know.">{{ old('admin_notes', $formSubmission->admin_notes) }}</textarea>
                        </div>

                        <div class="admin-form__actions">
                            <button type="submit" class="admin-btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            @endcan

            <div class="admin-form admin-form--full">
                <div class="admin-form__body">
                    <h2 class="admin-form__section-title">Technical</h2>
                    <dl class="admin-kv admin-kv--compact">
                        <div class="admin-kv__row">
                            <dt>Submitted</dt>
                            <dd>{{ $formSubmission->created_at?->diffForHumans() }}</dd>
                        </div>
                        @if($formSubmission->honeypot_caught)
                            <div class="admin-kv__row">
                                <dt>Spam flag</dt>
                                <dd>Honeypot caught this entry</dd>
                            </div>
                        @endif
                        <div class="admin-kv__row">
                            <dt>IP</dt>
                            <dd>{{ $formSubmission->ip ?: 'Not recorded' }}</dd>
                        </div>
                        <div class="admin-kv__row">
                            <dt>Browser</dt>
                            <dd class="admin-kv__muted">{{ $formSubmission->user_agent ?: 'Not recorded' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </aside>
    </div>
@endsection
