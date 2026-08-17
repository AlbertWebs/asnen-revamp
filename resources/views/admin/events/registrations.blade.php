@extends('layouts.admin')

@section('title', 'Registrations: '.$event->title)
@section('heading', 'Event registrations')

@section('content')
    <div class="admin-toolbar">
        <p class="admin-toolbar__copy">
            {{ $registrations->total() }} {{ \Illuminate\Support\Str::plural('person', $registrations->total()) }} registered for {{ $event->title }}.
        </p>
        <div class="admin-toolbar__actions">
            <a href="{{ route('admin.events.edit', $event) }}" class="admin-btn-secondary">Edit event</a>
        </div>
    </div>

    @can('events.update')
        <form method="POST" action="{{ route('admin.events.registrations.email', $event) }}" class="admin-form admin-form--wide mb-6">
            @csrf
            <div class="admin-form__body">
                <header class="admin-form__header">
                    <h2 class="admin-form__title">Email everyone who registered</h2>
                    <p class="admin-form__intro">Each person gets their own copy. You can use {name} and {event} in the subject or message.</p>
                </header>
                <div class="admin-form__section">
                    <div class="admin-field">
                        <label for="subject" class="admin-label">Subject</label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject', $event->title.': an update from ASNEN') }}" required class="admin-input" maxlength="150">
                    </div>
                    <div class="admin-field">
                        <label for="body" class="admin-label">Message</label>
                        <textarea name="body" id="body" rows="8" required class="admin-textarea" maxlength="8000">{{ old('body') }}</textarea>
                    </div>
                </div>
                <div class="admin-form__actions">
                    <button type="submit" class="admin-btn-primary" @disabled($registrations->total() === 0)>
                        Send to {{ $registrations->total() }} {{ \Illuminate\Support\Str::plural('registrant', $registrations->total()) }}
                    </button>
                </div>
            </div>
        </form>
    @endcan

    <div class="admin-table-card">
        <div class="px-4 pb-1 pt-5 sm:px-6">
            <h2 class="admin-form__section-title">Registrants</h2>
        </div>
        <div class="divide-y divide-charcoal/10">
            @forelse ($registrations as $row)
                <article class="px-4 py-5 sm:px-6">
                    <div class="mb-3 flex flex-wrap items-start justify-between gap-2">
                        <div>
                            <h3 class="text-base font-semibold text-charcoal">{{ $row->name }}</h3>
                            <p class="text-sm text-charcoal/55">Registered {{ $row->created_at?->format('j M Y, g:i A') }}</p>
                        </div>
                        <span class="admin-badge">{{ $row->consent_marketing ? 'Updates: yes' : 'Updates: no' }}</span>
                    </div>
                    <dl class="admin-kv admin-kv--compact">
                        <div class="admin-kv__row">
                            <dt>Email</dt>
                            <dd><a href="mailto:{{ $row->email }}" class="admin-kv__link">{{ $row->email }}</a></dd>
                        </div>
                        <div class="admin-kv__row">
                            <dt>Phone</dt>
                            <dd>
                                @if($row->phone)
                                    <a href="tel:{{ preg_replace('/[^\d+]/', '', $row->phone) }}" class="admin-kv__link">{{ $row->phone }}</a>
                                @else
                                    Not given
                                @endif
                            </dd>
                        </div>
                        <div class="admin-kv__row">
                            <dt>Organisation</dt>
                            <dd>{{ $row->organization ?: 'Not given' }}</dd>
                        </div>
                        <div class="admin-kv__row">
                            <dt>Notes</dt>
                            <dd>{{ $row->notes ?: 'Not given' }}</dd>
                        </div>
                        <div class="admin-kv__row">
                            <dt>Keep informed</dt>
                            <dd>{{ $row->consent_marketing ? 'Yes, send future event updates' : 'No' }}</dd>
                        </div>
                    </dl>
                </article>
            @empty
                <p class="admin-table__empty px-4 py-8">No registrations yet.</p>
            @endforelse
        </div>
    </div>

    <div class="admin-pagination">{{ $registrations->links() }}</div>
@endsection
