@extends('layouts.admin')

@section('title', 'Regions')
@section('heading', 'Impact Regions')

@section('content')
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <p class="max-w-2xl text-sm text-charcoal-600">
            Manage map pins for the public Impact by Region page. Set a name, description, and map coordinates for each place ASNEN has worked.
        </p>
        @can('regions.create')
            <a href="{{ route('admin.regions.create') }}" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">New region</a>
        @endcan
    </div>

    <div class="overflow-hidden rounded-lg border border-charcoal-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-charcoal-200">
            <thead class="bg-charcoal-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-charcoal-600">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-charcoal-600">Coordinates</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-charcoal-600">Featured</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-charcoal-600">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-charcoal-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-charcoal-100">
                @forelse ($regions as $item)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-charcoal-900">
                            {{ $item->name }}
                            @if($item->country)
                                <span class="mt-0.5 block text-xs font-normal text-charcoal-500">{{ $item->country }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-mono text-xs text-charcoal-600">
                            @if($item->hasCoordinates())
                                {{ number_format($item->latitude, 4) }}, {{ number_format($item->longitude, 4) }}
                            @else
                                <span class="text-charcoal-400">Not set</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-charcoal-600">{{ $item->is_featured ? 'Yes' : '—' }}</td>
                        <td class="px-4 py-3 text-sm text-charcoal-600">{{ $item->status?->value ?? $item->status }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            @can('regions.update')
                                <a href="{{ route('admin.regions.edit', $item) }}" class="text-forest-700 hover:underline">Edit</a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-sm text-charcoal-500">No regions yet. Add the first map pin.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $regions->links() }}</div>
@endsection
