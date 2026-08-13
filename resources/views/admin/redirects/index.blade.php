@extends('layouts.admin')

@section('title', 'Redirects')
@section('heading', 'Redirects')

@section('content')
    <div class="admin-toolbar">
        <p class="admin-toolbar__copy">
            Manage URL redirects, including merged pages such as Governance, Our Story, and Komolion.
        </p>
        <div class="admin-toolbar__actions">
            @can('redirects.create')
                <a href="{{ route('admin.redirects.create') }}" class="admin-btn-primary">New</a>
            @endcan
        </div>
    </div>

    <div class="admin-table-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">From</th>
                        <th scope="col">To</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($redirects as $item)
                        <tr>
                            <td class="admin-table__primary">{{ $item->from_path }}</td>
                            <td>{{ $item->to_path }}</td>
                            <td>
                                <span class="admin-badge admin-badge--{{ $item->is_active ? 'published' : 'archived' }}">
                                    {{ $item->status_code }} {{ $item->is_active ? 'active' : 'inactive' }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="admin-table__actions">
                                    @can('redirects.update')
                                        <a href="{{ route('admin.redirects.edit', $item) }}" class="admin-table__link">Edit</a>
                                    @endcan
                                    @include('admin.partials.table-delete', [
                                        'action' => route('admin.redirects.destroy', $item),
                                        'permission' => 'redirects.delete',
                                        'label' => (string) ($item->from_path ?? 'this redirect'),
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

    <div class="admin-pagination">{{ $redirects->links() }}</div>
@endsection
