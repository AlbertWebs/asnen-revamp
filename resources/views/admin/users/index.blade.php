@extends('layouts.admin')

@section('title', 'Users')
@section('heading', 'Users')

@section('content')
    <div class="admin-toolbar">
        <p class="admin-toolbar__copy">
            Manage admin users and their assigned roles.
        </p>
        <div class="admin-toolbar__actions"></div>
    </div>

    <div class="admin-table-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Roles</th>
                        <th scope="col" class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="admin-table__primary">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                            <td class="text-right">
                                @can('users.manage')
                                    <a href="{{ route('admin.users.edit', $user) }}" class="admin-table__link">Roles</a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="admin-table__empty">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-pagination">{{ $users->links() }}</div>
@endsection
