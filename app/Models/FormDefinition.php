<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormDefinition extends Model
{
    use HasSlug;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'fields',
        'success_message',
        'notify_emails',
        'is_active',
        'retention_days',
    ];

    protected function casts(): array
    {
        return [
            'fields' => 'array',
            'notify_emails' => 'array',
            'is_active' => 'boolean',
            'retention_days' => 'integer',
        ];
    }

    protected function getSlugSource(): string
    {
        return $this->name;
    }

    protected function getSlugSourceAttributes(): array
    {
        return ['name'];
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }

    /**
     * @param  list<string>|null  $emails
     * @return list<string>
     */
    public static function mergeNotifyEmails(?array $emails): array
    {
        $required = strtolower(trim((string) config('mail.form_notify_address', 'info@asnenafrica.org')));

        return collect($emails ?? [])
            ->push($required)
            ->filter(fn ($email) => is_string($email) && filter_var($email, FILTER_VALIDATE_EMAIL))
            ->map(fn ($email) => strtolower(trim($email)))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @return list<string>
     */
    public function notificationRecipients(): array
    {
        return self::mergeNotifyEmails($this->notify_emails);
    }
}
