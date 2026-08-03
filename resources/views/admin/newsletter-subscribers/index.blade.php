@extends('layouts.admin')

@section('title', 'Newsletter Subscribers')
@section('heading', 'Newsletter Subscribers')

@section('content')
    <div class="admin-toolbar">
        <p class="admin-toolbar__copy">
            View everyone who has subscribed to the newsletter.
        </p>
        <div class="admin-toolbar__actions">
            @can('newsletter.export')
                <a href="{{ route('admin.newsletter-subscribers.export') }}" class="admin-btn-secondary">Export CSV</a>
            @endcan
        </div>
    </div>

    <div class="admin-table-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">Email</th>
                        <th scope="col">Name</th>
                        <th scope="col">Status</th>
                        <th scope="col">Subscribed</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subscribers as $subscriber)
                        @php
                            $status = $subscriber->status?->value ?? $subscriber->status;
                        @endphp
                        <tr>
                            <td class="admin-table__primary">{{ $subscriber->email }}</td>
                            <td>{{ $subscriber->name }}</td>
                            <td>
                                <span class="admin-badge admin-badge--{{ $status }}">{{ str_replace('_', ' ', $status) }}</span>
                            </td>
                            <td>{{ $subscriber->created_at?->toDateString() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="admin-table__empty">No subscribers yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-pagination">{{ $subscribers->links() }}</div>
@endsection
