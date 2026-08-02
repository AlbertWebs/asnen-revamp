@extends('layouts.admin')

@section('title', 'Form Submissions')
@section('heading', 'Form Submissions')

@section('content')
    <div class="mb-4 flex justify-end">
        @can('form_submissions.export')
            <a href="{{ route('admin.form-submissions.export') }}" class="rounded-md border border-charcoal-300 bg-white px-4 py-2 text-sm font-medium text-charcoal-700 hover:bg-charcoal-50">Export CSV</a>
        @endcan
    </div>

    <div class="overflow-hidden rounded-lg border border-charcoal-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-charcoal-200">
            <thead class="bg-charcoal-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-charcoal-600">Form</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-charcoal-600">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-charcoal-600">Submitted</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-charcoal-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-charcoal-100">
                @forelse ($submissions as $submission)
                    <tr>
                        <td class="px-4 py-3 text-sm text-charcoal-900">{{ $submission->formDefinition?->name }}</td>
                        <td class="px-4 py-3 text-sm text-charcoal-600">{{ $submission->status?->value ?? $submission->status }}</td>
                        <td class="px-4 py-3 text-sm text-charcoal-600">{{ $submission->created_at?->diffForHumans() }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            <a href="{{ route('admin.form-submissions.show', $submission) }}" class="text-forest-700 hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-sm text-charcoal-500">No submissions yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $submissions->links() }}</div>
@endsection
