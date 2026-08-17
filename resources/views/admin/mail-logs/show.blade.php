@php
    $status = $mailLog->status?->value ?? $mailLog->status;
@endphp

@extends('layouts.admin')

@section('title', 'Mail #'.$mailLog->id)
@section('heading', $mailLog->subject ?: 'Mail #'.$mailLog->id)

@section('content')
    <div class="admin-toolbar">
        <div>
            <a href="{{ route('admin.mail-logs.index') }}" class="admin-table__link px-0">All mail</a>
            <p class="admin-toolbar__copy mt-1">
                #{{ $mailLog->id }}
                · {{ $mailLog->created_at?->timezone(config('app.timezone'))->format('j M Y, g:i A') }}
            </p>
        </div>
        <div class="admin-toolbar__actions">
            <span class="admin-badge admin-badge--{{ $status === 'sent' ? 'published' : ($status === 'failed' ? 'rejected' : 'pending') }}">{{ $mailLog->status?->label() ?? $status }}</span>
        </div>
    </div>

    <div class="admin-form admin-form--full">
        <div class="admin-form__body">
            <dl class="admin-kv">
                <div class="admin-kv__row">
                    <dt>To</dt>
                    <dd>{{ $mailLog->toList() }}</dd>
                </div>
                <div class="admin-kv__row">
                    <dt>From</dt>
                    <dd>{{ $mailLog->from_name ? $mailLog->from_name.' <'.$mailLog->from_address.'>' : ($mailLog->from_address ?: '—') }}</dd>
                </div>
                <div class="admin-kv__row">
                    <dt>Reply-To</dt>
                    <dd>{{ collect($mailLog->reply_to_addresses ?? [])->implode(', ') ?: '—' }}</dd>
                </div>
                @if($mailLog->cc_addresses)
                    <div class="admin-kv__row">
                        <dt>CC</dt>
                        <dd>{{ collect($mailLog->cc_addresses)->implode(', ') }}</dd>
                    </div>
                @endif
                @if($mailLog->bcc_addresses)
                    <div class="admin-kv__row">
                        <dt>BCC</dt>
                        <dd>{{ collect($mailLog->bcc_addresses)->implode(', ') }}</dd>
                    </div>
                @endif
                <div class="admin-kv__row">
                    <dt>Subject</dt>
                    <dd>{{ $mailLog->subject ?: '—' }}</dd>
                </div>
                <div class="admin-kv__row">
                    <dt>Type</dt>
                    <dd>{{ $mailLog->mailable ?: '—' }}</dd>
                </div>
                <div class="admin-kv__row">
                    <dt>Mailer</dt>
                    <dd>{{ $mailLog->mailer ?: '—' }}</dd>
                </div>
                <div class="admin-kv__row">
                    <dt>Accepted at</dt>
                    <dd>{{ $mailLog->sent_at?->timezone(config('app.timezone'))->format('j M Y, g:i A') ?: 'Not accepted' }}</dd>
                </div>
                <div class="admin-kv__row">
                    <dt>Message ID</dt>
                    <dd>{{ $mailLog->message_id ?: '—' }}</dd>
                </div>
                @if($mailLog->error)
                    <div class="admin-kv__row">
                        <dt>Error</dt>
                        <dd>{{ $mailLog->error }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>
@endsection
