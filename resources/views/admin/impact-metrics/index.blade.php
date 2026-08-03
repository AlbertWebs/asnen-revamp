@extends('layouts.admin')

@section('title', 'Impact Metrics')
@section('heading', 'Impact Metrics')

@section('content')
    <div class="admin-toolbar">
        <p class="admin-toolbar__copy">
            Manage impact metrics displayed on the public site.
        </p>
        <div class="admin-toolbar__actions">
            @can('impact_metrics.create')
                <a href="{{ route('admin.impact-metrics.create') }}" class="admin-btn-primary">New</a>
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
                    @forelse ($metrics as $item)
                        @php
                            $status = $item->status?->value ?? $item->status ?? ($item->is_active ?? false ? 'active' : 'inactive');
                        @endphp
                        <tr>
                            <td class="admin-table__primary">{{ $item->question }}</td>
                            <td>
                                <span class="admin-badge admin-badge--{{ $status }}">{{ str_replace('_', ' ', $status) }}</span>
                            </td>
                            <td class="text-right">
                                <div class="admin-table__actions">
                                    @can('impact_metrics.update')
                                        <a href="{{ route('admin.impact-metrics.edit', $item) }}" class="admin-table__link">Edit</a>
                                    @endcan
                                    @include('admin.partials.table-delete', [
                                        'action' => route('admin.impact-metrics.destroy', $item),
                                        'permission' => 'impact_metrics.delete',
                                        'label' => (string) ($item->label ?? $item->question ?? 'this metric'),
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

    <div class="admin-pagination">{{ $metrics->links() }}</div>
@endsection
