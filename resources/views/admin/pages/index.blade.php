@extends('layouts.admin')

@section('title', 'Pages')
@section('heading', 'Pages')

@section('content')
    <div class="admin-toolbar">
        <p class="admin-toolbar__copy">
            Manage site pages, drafts, and publishing status.
        </p>
        <div class="admin-toolbar__actions">
            @can('pages.create')
                <a href="{{ route('admin.pages.create') }}" class="admin-btn-primary">New page</a>
            @endcan
        </div>
    </div>

    <div class="admin-table-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Status</th>
                        <th scope="col">Updated</th>
                        <th scope="col" class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pages as $page)
                        @php
                            $status = $page->status?->value ?? $page->status;
                        @endphp
                        <tr>
                            <td class="admin-table__primary">{{ $page->title }}</td>
                            <td>
                                <span class="admin-badge admin-badge--{{ $status }}">{{ str_replace('_', ' ', $status) }}</span>
                            </td>
                            <td>{{ $page->updated_at?->diffForHumans() }}</td>
                            <td class="text-right">
                                <div class="admin-table__actions">
                                    @can('pages.update')
                                        <a href="{{ route('admin.pages.edit', $page) }}" class="admin-table__link">Edit</a>
                                    @endcan
                                    @include('admin.partials.table-delete', [
                                        'action' => route('admin.pages.destroy', $page),
                                        'permission' => 'pages.delete',
                                        'label' => $page->title,
                                    ])
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="admin-table__empty">No pages yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-pagination">{{ $pages->links() }}</div>
@endsection
