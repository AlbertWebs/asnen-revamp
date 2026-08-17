@extends('layouts.admin')

@section('title', 'Form Submissions')
@section('heading', 'Form Submissions')

@section('content')
    <div class="admin-toolbar">
        <p class="admin-toolbar__copy">
            Review submissions from public site forms.
        </p>
        <div class="admin-toolbar__actions">
            @can('form_submissions.export')
                <a href="{{ route('admin.form-submissions.export') }}" class="admin-btn-secondary">Export CSV</a>
            @endcan
        </div>
    </div>

    <div class="admin-table-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                                <th scope="col">Form</th>
                                <th scope="col">From</th>
                                <th scope="col">Status</th>
                                <th scope="col">Submitted</th>
                                <th scope="col" class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($submissions as $submission)
                        @php
                            $status = $submission->status?->value ?? $submission->status;
                        @endphp
                        <tr>
                            <td class="admin-table__primary">{{ $submission->formDefinition?->name }}</td>
                            <td>
                                <span class="block font-medium text-charcoal">{{ $submission->contactName() ?: 'No name' }}</span>
                                @if($submission->contactEmail())
                                    <span class="block text-xs text-charcoal/55">{{ $submission->contactEmail() }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="admin-badge admin-badge--{{ $status }}">{{ str_replace('_', ' ', $status) }}</span>
                            </td>
                            <td>{{ $submission->created_at?->diffForHumans() }}</td>
                            <td class="text-right">
                                <a href="{{ route('admin.form-submissions.show', $submission) }}" class="admin-table__link">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="admin-table__empty">No submissions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-pagination">{{ $submissions->links() }}</div>
@endsection
