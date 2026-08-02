@extends('layouts.admin')

@section('title', 'Pages')
@section('heading', 'Pages')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        @can('pages.create')
            <a href="{{ route('admin.pages.create') }}" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">New page</a>
        @endcan
    </div>

    <div class="overflow-hidden rounded-lg border border-charcoal-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-charcoal-200">
            <thead class="bg-charcoal-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-charcoal-600">Title</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-charcoal-600">Status</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-charcoal-600">Updated</th>
                    <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-charcoal-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-charcoal-100">
                @forelse ($pages as $page)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-charcoal-900">{{ $page->title }}</td>
                        <td class="px-4 py-3 text-sm text-charcoal-600">{{ $page->status?->value ?? $page->status }}</td>
                        <td class="px-4 py-3 text-sm text-charcoal-600">{{ $page->updated_at?->diffForHumans() }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            @can('pages.update')
                                <a href="{{ route('admin.pages.edit', $page) }}" class="text-forest-700 hover:underline">Edit</a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-sm text-charcoal-500">No pages yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $pages->links() }}</div>
@endsection
