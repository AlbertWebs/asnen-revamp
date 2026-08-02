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
                    <button type="submit" class="rounded-md border border-charcoal-300 bg-white px-3 py-1.5 text-sm text-charcoal-700 hover:bg-charcoal-50">Unpublish</button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.'.$routePrefix.'.publish', $model) }}">
                    @csrf
                    <button type="submit" class="rounded-md bg-forest-700 px-3 py-1.5 text-sm font-medium text-white hover:bg-forest-800">Publish</button>
                </form>
            @endif
        @endcan
    </div>
@endif
