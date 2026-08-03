@extends('layouts.admin')

@section('title', 'Donation Campaigns')
@section('heading', 'Donation Campaigns')

@section('content')
    <div class="admin-toolbar">
        <p class="admin-toolbar__copy">
            Manage fundraising campaigns and their publish status.
        </p>
        <div class="admin-toolbar__actions">
            @can('donations.create')
                <a href="{{ route('admin.donation-campaigns.create') }}" class="admin-btn-primary">New</a>
            @endcan
        </div>
    </div>

    <div class="admin-table-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($campaigns as $item)
                        @php
                            $status = $item->status?->value ?? $item->status ?? ($item->is_active ?? false ? 'active' : 'inactive');
                        @endphp
                        <tr>
                            <td class="admin-table__primary">{{ $item->title }}</td>
                            <td>
                                <span class="admin-badge admin-badge--{{ $status }}">{{ str_replace('_', ' ', $status) }}</span>
                            </td>
                            <td class="text-right">
                                <div class="admin-table__actions">
                                    @can('donations.update')
                                        <a href="{{ route('admin.donation-campaigns.edit', $item) }}" class="admin-table__link">Edit</a>
                                    @endcan
                                    @include('admin.partials.table-delete', [
                                        'action' => route('admin.donation-campaigns.destroy', $item),
                                        'permission' => 'donations.delete',
                                        'label' => $item->title,
                                    ])
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="admin-table__empty">No records yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-pagination">{{ $campaigns->links() }}</div>
@endsection
