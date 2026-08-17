@extends('layouts.admin')

@section('title', 'Mail log')
@section('heading', 'Mail log')

@section('content')
    <div class="admin-toolbar">
        <p class="admin-toolbar__copy">
            Outgoing mail from the site, including who it was sent to and whether SES accepted it.
        </p>
        <form method="GET" action="{{ route('admin.mail-logs.index') }}" class="admin-toolbar__actions" style="display:flex;gap:.5rem;flex-wrap:wrap;align-items:center;">
            <label class="sr-only" for="mail-log-q">Search</label>
            <input id="mail-log-q" type="search" name="q" value="{{ $search }}" placeholder="Search to, subject…" class="admin-input" style="min-width:14rem;">
            <label class="sr-only" for="mail-log-status">Status</label>
            <select id="mail-log-status" name="status" class="admin-select">
                <option value="">All statuses</option>
                @foreach (\App\Enums\MailLogStatus::cases() as $option)
                    <option value="{{ $option->value }}" @selected($status === $option->value)>{{ $option->label() }}</option>
                @endforeach
            </select>
            <button type="submit" class="admin-btn-secondary">Filter</button>
        </form>
    </div>

    <div class="admin-table-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">When</th>
                        <th scope="col">To</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Type</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        @php $statusValue = $log->status?->value ?? $log->status; @endphp
                        <tr>
                            <td>{{ $log->sent_at?->timezone(config('app.timezone'))->format('j M Y, g:i A') ?: $log->created_at?->timezone(config('app.timezone'))->format('j M Y, g:i A') }}</td>
                            <td class="admin-table__primary">{{ $log->toList() }}</td>
                            <td>{{ $log->subject ?: '—' }}</td>
                            <td>{{ $log->mailable ?: '—' }}</td>
                            <td>
                                <span class="admin-badge admin-badge--{{ $statusValue === 'sent' ? 'published' : ($statusValue === 'failed' ? 'rejected' : 'pending') }}">{{ $log->status?->label() ?? $statusValue }}</span>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.mail-logs.show', $log) }}" class="admin-table__link">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="admin-table__empty">No mail has been logged yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-pagination">{{ $logs->links() }}</div>
@endsection
