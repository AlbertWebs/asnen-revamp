@extends('layouts.admin')

@section('title', 'Galleries')
@section('heading', 'Galleries')

@section('content')
    <div class="admin-toolbar">
        <p class="admin-toolbar__copy">
            Manage photo galleries and their publish status.
        </p>
        <div class="admin-toolbar__actions">
            @can('galleries.create')
                <a href="{{ route('admin.galleries.create') }}" class="admin-btn-primary">New</a>
            @endcan
        </div>
    </div>

    <div class="admin-table-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Photos</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($galleries as $item)
                        @php
                            $status = $item->status?->value ?? $item->status;
                        @endphp
                        <tr>
                            <td class="admin-table__primary">{{ $item->title }}</td>
                            <td>{{ $item->items_count ?? $item->items()->count() }}</td>
                            <td>
                                <span class="admin-badge admin-badge--{{ $status }}">{{ str_replace('_', ' ', $status) }}</span>
                            </td>
                            <td class="text-right">
                                <div class="admin-table__actions">
                                    @can('galleries.update')
                                        <a href="{{ route('admin.galleries.edit', $item) }}" class="admin-table__link">Edit</a>
                                    @endcan
                                    @include('admin.partials.table-delete', [
                                        'action' => route('admin.galleries.destroy', $item),
                                        'permission' => 'galleries.delete',
                                        'label' => $item->title,
                                    ])
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="admin-table__empty">No records yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-pagination">{{ $galleries->links() }}</div>
@endsection
