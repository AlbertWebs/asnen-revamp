@props(['model', 'routePrefix'])

@php
    $permissionMap = [
        'donation-campaigns' => 'donations',
        'team-members' => 'team_members',
        'impact-stories' => 'impact_stories',
        'impact-metrics' => 'impact_metrics',
    ];
    $permissionPrefix = $permissionMap[$routePrefix] ?? str_replace('-', '_', $routePrefix);
@endphp

@if ($model->exists)
    <div class="flex flex-wrap gap-2">
        @can($permissionPrefix.'.publish')
            @if ($model->status?->value === 'published' || (string) $model->status === 'published')
                <form method="POST" action="{{ route('admin.'.$routePrefix.'.unpublish', $model) }}">
                    @csrf
                    <button type="submit" class="admin-btn-secondary">Unpublish</button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.'.$routePrefix.'.publish', $model) }}">
                    @csrf
                    <button type="submit" class="admin-btn-primary">Publish</button>
                </form>
            @endif
        @endcan
    </div>
@endif
