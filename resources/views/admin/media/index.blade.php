@extends('layouts.admin')

@section('title', 'Media Library')
@section('heading', 'Media Library')

@section('content')
    <div class="admin-toolbar">
        <p class="admin-toolbar__copy">
            Upload images here, then attach them on Programs, Stories, Events, Partners, Team, Publications, or Home page blocks.
        </p>
        <div class="admin-toolbar__actions">
            @can('media.upload')
                <a href="{{ route('admin.media.create') }}" class="admin-btn-primary">Upload</a>
            @endcan
        </div>
    </div>

    <div class="admin-card-grid">
        @forelse ($assets as $asset)
            <div class="admin-card">
                <div class="admin-card__media">
                    @php $previewUrl = $asset->publicUrl(); @endphp
                    @if ($asset->isImage() && $previewUrl)
                        <img src="{{ $previewUrl }}" alt="{{ e($asset->alt ?? $asset->filename) }}" class="h-full w-full object-cover">
                    @elseif ($asset->isImage())
                        <span class="text-xs text-charcoal/50">Broken file (re-upload)</span>
                    @else
                        <x-admin.file-format-icon :kind="$asset->formatKind()" :label="$asset->formatLabel()" />
                    @endif
                </div>
                <div class="admin-card__body">
                    <p class="admin-card__title" title="{{ $asset->filename }}">{{ $asset->filename }}</p>
                    <p class="admin-card__meta">
                        <span class="admin-card__format">{{ $asset->formatLabel() }}</span>
                        <span aria-hidden="true">·</span>
                        <span>{{ $asset->consent_status?->value ?? $asset->consent_status }}</span>
                    </p>
                    <div class="flex items-center gap-3">
                        @can('media.update')
                            <a href="{{ route('admin.media.edit', $asset) }}" class="admin-table__link">Edit</a>
                        @endcan
                        @include('admin.partials.table-delete', [
                            'action' => route('admin.media.destroy', $asset),
                            'permission' => 'media.delete',
                            'label' => $asset->filename,
                        ])
                    </div>
                </div>
            </div>
        @empty
            <p class="col-span-full py-8 text-center text-sm text-charcoal/50">No media uploaded yet.</p>
        @endforelse
    </div>

    <div class="admin-pagination">{{ $assets->links() }}</div>
@endsection
