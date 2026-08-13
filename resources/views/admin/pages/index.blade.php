@extends('layouts.admin')

@section('title', 'Pages')
@section('heading', 'Pages')

@section('content')
    <div class="admin-toolbar">
        <p class="admin-toolbar__copy">
            Pages are grouped to match the public site menu. Nested items sit under the same parent as on the website, including merged copy such as Vision, Our Story, Governance, and Komolion.
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
                        <th scope="col">Page</th>
                        <th scope="col">Status</th>
                        <th scope="col">Updated</th>
                        <th scope="col" class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sections as $section)
                        <tr class="admin-table__section">
                            <td colspan="4">{{ $section['label'] }}</td>
                        </tr>
                        @foreach ($section['rows'] as $row)
                            @php
                                $page = $row['page'];
                                $depth = (int) $row['depth'];
                                $status = $page->status?->value ?? $page->status;
                            @endphp
                            <tr class="{{ $depth > 0 ? 'admin-table__depth-'.$depth : '' }}">
                                <td class="admin-table__primary">
                                    @if($depth > 0)
                                        <span class="admin-table__tree" aria-hidden="true">↳</span>
                                    @endif
                                    {{ $page->title }}
                                    <span class="admin-table__meta">{{ $page->publicPath() }}</span>
                                    @if($page->mergedDestinationLabel())
                                        <span class="admin-table__meta">Appears on {{ $page->mergedDestinationLabel() }}</span>
                                    @endif
                                </td>
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
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="4" class="admin-table__empty">No pages yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
