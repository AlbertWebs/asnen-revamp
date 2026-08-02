@extends('layouts.admin')

@section('title', 'Users')
@section('heading', 'Users')

@section('content')
    <div class="overflow-hidden rounded-lg border border-charcoal-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-charcoal-200">
            <thead class="bg-charcoal-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-charcoal-600">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-charcoal-600">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-charcoal-600">Roles</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-charcoal-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-charcoal-100">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-charcoal-900">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-sm text-charcoal-600">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-sm text-charcoal-600">{{ $user->roles->pluck('name')->join(', ') }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            @can('users.manage')
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-forest-700 hover:underline">Roles</a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-sm text-charcoal-500">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
@endsection
