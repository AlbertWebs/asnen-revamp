@extends('layouts.admin')

@section('title', 'Events')
@section('heading', 'Events')

@section('content')
    <div class="admin-toolbar">
        <p class="admin-toolbar__copy">
            Events are grouped as ongoing, upcoming, then past.
        </p>
        <div class="admin-toolbar__actions">
            @can('events.create')
                <a href="{{ route('admin.events.create') }}" class="admin-btn-primary">New</a>
            @endcan
        </div>
    </div>

    @foreach ([
        'ongoing' => ['label' => 'Ongoing', 'items' => $ongoing, 'empty' => 'No events are running right now.'],
        'upcoming' => ['label' => 'Upcoming', 'items' => $upcoming, 'empty' => 'No upcoming events yet.'],
        'past' => ['label' => 'Past', 'items' => $past, 'empty' => 'No past events yet.'],
    ] as $groupKey => $group)
        <section class="admin-events-group" aria-labelledby="events-{{ $groupKey }}-heading">
            <h2 id="events-{{ $groupKey }}-heading" class="admin-events-group__title">
                {{ $group['label'] }}
                <span class="admin-events-group__count">{{ $group['items']->count() }}</span>
            </h2>

            <div class="admin-table-card">
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">When</th>
                                <th scope="col">Timing</th>
                                <th scope="col">Status</th>
                                <th scope="col">Registered</th>
                                <th scope="col">Sign-up form</th>
                                <th scope="col" class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($group['items'] as $item)
                                @php
                                    $status = $item->status?->value ?? $item->status ?? 'draft';
                                    $when = $item->starts_at
                                        ? ($item->ends_at
                                            ? $item->starts_at->format('j M Y, g:i A').' - '.$item->ends_at->format('g:i A')
                                            : $item->starts_at->format('j M Y, g:i A'))
                                        : 'Date not set';
                                @endphp
                                <tr>
                                    <td class="admin-table__primary">{{ $item->title }}</td>
                                    <td>{{ $when }}</td>
                                    <td>
                                        <span class="admin-badge admin-badge--{{ $groupKey }}">{{ $item->timingLabel() }}</span>
                                    </td>
                                    <td>
                                        <span class="admin-badge admin-badge--{{ $status }}">{{ str_replace('_', ' ', $status) }}</span>
                                    </td>
                                    <td>{{ $item->registrations_count ?? 0 }}</td>
                                    <td>
                                        @can('events.update')
                                            <form method="POST" action="{{ route('admin.events.registration.toggle', $item) }}">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="admin-switch {{ $item->allow_registration ? 'is-on' : '' }}"
                                                    role="switch"
                                                    aria-checked="{{ $item->allow_registration ? 'true' : 'false' }}"
                                                    aria-label="{{ $item->allow_registration ? 'Turn registration off' : 'Turn registration on' }}"
                                                >
                                                    <span class="admin-switch__knob" aria-hidden="true"></span>
                                                    <span class="admin-switch__label">{{ $item->allow_registration ? 'On' : 'Off' }}</span>
                                                </button>
                                            </form>
                                        @else
                                            <span class="admin-badge">{{ $item->allow_registration ? 'On' : 'Off' }}</span>
                                        @endcan
                                    </td>
                                    <td class="text-right">
                                        <div class="admin-table__actions">
                                            @can('events.view')
                                                <a href="{{ route('admin.events.registrations', $item) }}" class="admin-table__link">Registrants</a>
                                            @endcan
                                            @can('events.update')
                                                <a href="{{ route('admin.events.edit', $item) }}" class="admin-table__link">Edit</a>
                                            @endcan
                                            @include('admin.partials.table-delete', [
                                                'action' => route('admin.events.destroy', $item),
                                                'permission' => 'events.delete',
                                                'label' => $item->title,
                                            ])
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="admin-table__empty">{{ $group['empty'] }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    @endforeach
@endsection
