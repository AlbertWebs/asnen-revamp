@extends('layouts.admin')

@section('title', 'Galleries')
@section('heading', 'Galleries')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        @can('galleries.create')
            <a href="{{ route('admin.galleries.create') }}" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">New</a>
        @endcan
    </div>

    <div class="overflow-hidden rounded-lg border border-charcoal-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-charcoal-200">
            <thead class="bg-charcoal-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-charcoal-600">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-charcoal-600">Photos</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-charcoal-600">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-charcoal-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-charcoal-100">
                @forelse ($galleries as $item)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-charcoal-900">{{ $item->title }}</td>
                        <td class="px-4 py-3 text-sm text-charcoal-600">{{ $item->items_count ?? $item->items()->count() }}</td>
                        <td class="px-4 py-3 text-sm text-charcoal-600">{{ $item->status?->value ?? $item->status }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            @can('galleries.update')
                                <a href="{{ route('admin.galleries.edit', $item) }}" class="text-forest-700 hover:underline">Edit</a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-sm text-charcoal-500">No records yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $galleries->links() }}</div>
@endsection