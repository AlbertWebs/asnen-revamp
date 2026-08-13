@extends('layouts.admin')

@section('title', 'Navigation')
@section('heading', 'Navigation')

@section('content')
    <div class="admin-toolbar">
        <p class="admin-toolbar__copy">
            Manage the menus used across the public site. About and Impact dropdowns should match the live header: Who We Are, Leadership &amp; Governance, and Success Stories.
        </p>
        <div class="admin-toolbar__actions"></div>
    </div>

    <div class="admin-table-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">Menu</th>
                        <th scope="col">Location</th>
                        <th scope="col">Items</th>
                        <th scope="col" class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($menus as $menu)
                        <tr>
                            <td class="admin-table__primary">{{ $menu->name }}</td>
                            <td>{{ $menu->location }}</td>
                            <td>{{ $menu->items_count }}</td>
                            <td class="text-right">
                                @can('navigation.update')
                                    <a href="{{ route('admin.navigation.edit', $menu) }}" class="admin-table__link">Edit</a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="admin-table__empty">No navigation menus yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
