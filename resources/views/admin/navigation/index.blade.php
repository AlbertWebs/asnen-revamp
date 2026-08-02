@extends('layouts.admin')

@section('title', 'Navigation')
@section('heading', 'Navigation')

@section('content')
    <div class="overflow-hidden rounded-lg border border-charcoal-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-charcoal-200">
            <thead class="bg-charcoal-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-charcoal-600">Menu</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-charcoal-600">Location</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-charcoal-600">Items</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-charcoal-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-charcoal-100">
                @forelse ($menus as $menu)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-charcoal-900">{{ $menu->name }}</td>
                        <td class="px-4 py-3 text-sm text-charcoal-600">{{ $menu->location }}</td>
                        <td class="px-4 py-3 text-sm text-charcoal-600">{{ $menu->items_count }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            @can('navigation.update')
                                <a href="{{ route('admin.navigation.edit', $menu) }}" class="text-forest-700 hover:underline">Edit</a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-sm text-charcoal-500">No navigation menus yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
