@extends('layouts.admin')

@section('title', 'Newsletter Subscribers')
@section('heading', 'Newsletter Subscribers')

@section('content')
    <div class="mb-4 flex justify-end">
        @can('newsletter.export')
            <a href="{{ route('admin.newsletter-subscribers.export') }}" class="rounded-md border border-charcoal-300 bg-white px-4 py-2 text-sm font-medium text-charcoal-700 hover:bg-charcoal-50">Export CSV</a>
        @endcan
    </div>

    <div class="overflow-hidden rounded-lg border border-charcoal-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-charcoal-200">
            <thead class="bg-charcoal-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-charcoal-600">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-charcoal-600">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-charcoal-600">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-charcoal-600">Subscribed</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-charcoal-100">
                @forelse ($subscribers as $subscriber)
                    <tr>
                        <td class="px-4 py-3 text-sm text-charcoal-900">{{ $subscriber->email }}</td>
                        <td class="px-4 py-3 text-sm text-charcoal-600">{{ $subscriber->name }}</td>
                        <td class="px-4 py-3 text-sm text-charcoal-600">{{ $subscriber->status }}</td>
                        <td class="px-4 py-3 text-sm text-charcoal-600">{{ $subscriber->created_at?->toDateString() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-sm text-charcoal-500">No subscribers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $subscribers->links() }}</div>
@endsection
