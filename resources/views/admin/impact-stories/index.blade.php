@extends('layouts.admin')

@section('title', 'Success Stories')
@section('heading', 'Success Stories')

@section('content')
    <div class="admin-toolbar">
        <p class="admin-toolbar__copy">
            Manage success stories published under Impact, including the Komolion case study.
        </p>
        <div class="admin-toolbar__actions">
            @can('impact_stories.create')
                <a href="{{ route('admin.impact-stories.create') }}" class="admin-btn-primary">New</a>
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
                    @forelse ($stories as $item)
                        @php
                            $status = $item->status?->value ?? $item->status ?? ($item->is_active ?? false ? 'active' : 'inactive');
                        @endphp
                        <tr>
                            <td class="admin-table__primary">
                                {{ $item->title }}
                                @if($item->slug)
                                    <span class="admin-table__meta">{{ $item->slug }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="admin-badge admin-badge--{{ $status }}">{{ str_replace('_', ' ', $status) }}</span>
                            </td>
                            <td class="text-right">
                                <div class="admin-table__actions">
                                    @if($item->isPublished())
                                        <a href="{{ $item->publicUrl() }}" class="admin-table__link" target="_blank" rel="noopener">View</a>
                                    @endif
                                    @can('impact_stories.update')
                                        <a href="{{ route('admin.impact-stories.edit', $item) }}" class="admin-table__link">Edit</a>
                                    @endcan
                                    @include('admin.partials.table-delete', [
                                        'action' => route('admin.impact-stories.destroy', $item),
                                        'permission' => 'impact_stories.delete',
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

    <div class="admin-pagination">{{ $stories->links() }}</div>
@endsection
