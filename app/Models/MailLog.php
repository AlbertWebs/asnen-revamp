<?php

namespace App\Models;

use App\Enums\MailLogStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{
    protected $fillable = [
        'mailer',
        'mailable',
        'from_address',
        'from_name',
        'to_addresses',
        'cc_addresses',
        'bcc_addresses',
        'reply_to_addresses',
        'subject',
        'status',
        'error',
        'message_id',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'to_addresses' => 'array',
            'cc_addresses' => 'array',
            'bcc_addresses' => 'array',
            'reply_to_addresses' => 'array',
            'status' => MailLogStatus::class,
            'sent_at' => 'datetime',
        ];
    }

    public function toList(): string
    {
        return collect($this->to_addresses ?? [])->filter()->implode(', ') ?: '—';
    }

    public function markSent(?string $messageId = null): void
    {
        $this->update([
            'status' => MailLogStatus::Sent,
            'error' => null,
            'message_id' => $messageId ?: $this->message_id,
            'sent_at' => now(),
        ]);
    }

    public function markFailed(string $error): void
    {
        $this->update([
            'status' => MailLogStatus::Failed,
            'error' => $error,
        ]);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);
        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $inner) use ($term) {
            $inner->where('subject', 'like', '%'.$term.'%')
                ->orWhere('from_address', 'like', '%'.$term.'%')
                ->orWhere('to_addresses', 'like', '%'.$term.'%')
                ->orWhere('mailable', 'like', '%'.$term.'%');
        });
    }

    public static function failLatest(string $error): void
    {
        $log = static::query()
            ->where('status', MailLogStatus::Sending)
            ->where('created_at', '>=', now()->subMinutes(2))
            ->latest('id')
            ->first();

        $log?->markFailed($error);
    }
}
