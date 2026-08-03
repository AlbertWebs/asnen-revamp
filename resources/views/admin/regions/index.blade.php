@extends('layouts.admin')

@section('title', 'Regions')
@section('heading', 'Impact Regions')

@section('content')
    <div class="admin-toolbar">
        <p class="admin-toolbar__copy">
            Manage map pins for the public Impact by Region page. Set a name, description, and map coordinates for each place ASNEN has worked.
        </p>
        <div class="admin-toolbar__actions">
            @can('regions.create')
                <a href="{{ route('admin.regions.create') }}" class="admin-btn-primary">New region</a>
            @endcan
        </div>
    </div>

    <div class="admin-table-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Coordinates</th>
                        <th scope="col">Featured</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($regions as $item)
                        @php
                            $status = $item->status?->value ?? $item->status;
                        @endphp
                        <tr>
                            <td class="admin-table__primary">
                                {{ $item->name }}
                                @if($item->country)
                                    <span class="admin-table__meta">{{ $item->country }}</span>
                                @endif
                            </td>
                            <td class="font-mono text-xs">
                                @if($item->hasCoordinates())
                                    {{ number_format($item->latitude, 4) }}, {{ number_format($item->longitude, 4) }}
                                @else
                                    <span class="text-charcoal/40">Not set</span>
                                @endif
                            </td>
                            <td>{{ $item->is_featured ? 'Yes' : '—' }}</td>
                            <td>
                                <span class="admin-badge admin-badge--{{ $status }}">{{ str_replace('_', ' ', $status) }}</span>
                            </td>
                            <td class="text-right">
                                <div class="admin-table__actions">
                                    @can('regions.update')
                                        <a href="{{ route('admin.regions.edit', $item) }}" class="admin-table__link">Edit</a>
                                    @endcan
                                    @include('admin.partials.table-delete', [
                                        'action' => route('admin.regions.destroy', $item),
                                        'permission' => 'regions.delete',
                                        'label' => $item->name,
                                    ])
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="admin-table__empty">No regions yet. Add the first map pin.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-pagination">{{ $regions->links() }}</div>
@endsection
