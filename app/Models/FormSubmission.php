<?php

namespace App\Models;

use App\Enums\FormSubmissionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormSubmission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'form_definition_id',
        'data',
        'status',
        'assigned_to',
        'admin_notes',
        'honeypot_caught',
        'ip',
        'user_agent',
        'consent_at',
        'confirmation_token',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'status' => FormSubmissionStatus::class,
            'honeypot_caught' => 'boolean',
            'consent_at' => 'datetime',
        ];
    }

    public function formDefinition(): BelongsTo
    {
        return $this->belongsTo(FormDefinition::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function statusLabel(): string
    {
        $status = $this->status instanceof FormSubmissionStatus
            ? $this->status->value
            : (string) $this->status;

        return ucfirst(str_replace('_', ' ', $status));
    }

    public function contactName(): ?string
    {
        $data = $this->data ?? [];

        foreach (['name', 'full_name', 'contact_name'] as $key) {
            if (filled($data[$key] ?? null)) {
                return (string) $data[$key];
            }
        }

        return null;
    }

    public function contactEmail(): ?string
    {
        $email = $this->data['email'] ?? null;

        return filled($email) ? (string) $email : null;
    }

    public function contactPhone(): ?string
    {
        $phone = $this->data['phone'] ?? null;

        return filled($phone) ? (string) $phone : null;
    }

    /**
     * @return list<array{key: string, label: string, value: string, href: ?string}>
     */
    public function displayRows(): array
    {
        $skip = ['website', 'math_token', 'math_answer', '_token', 'honeypot'];
        $fieldMeta = collect($this->formDefinition?->fields ?? [])
            ->filter(fn ($field) => is_array($field) && filled($field['name'] ?? null))
            ->keyBy('name');

        $rows = [];
        foreach ($this->data ?? [] as $key => $value) {
            if (in_array((string) $key, $skip, true)) {
                continue;
            }

            $meta = $fieldMeta->get($key, []);
            $type = (string) ($meta['type'] ?? '');
            $label = (string) ($meta['label'] ?? str_replace('_', ' ', ucfirst((string) $key)));
            $text = $this->stringifyValue($value);
            $href = null;

            if ($text !== '' && ($type === 'email' || $key === 'email') && filter_var($text, FILTER_VALIDATE_EMAIL)) {
                $href = 'mailto:'.$text;
            } elseif ($text !== '' && in_array($type, ['tel', 'phone'], true) || ($text !== '' && $key === 'phone')) {
                $href = 'tel:'.preg_replace('/[^\d+]/', '', $text);
            } elseif ($text !== '' && ($type === 'url' || str_starts_with($text, 'http://') || str_starts_with($text, 'https://'))) {
                $href = $text;
            }

            $rows[] = [
                'key' => (string) $key,
                'label' => $label,
                'value' => $text === '' ? 'Not given' : $text,
                'href' => $href,
            ];
        }

        return $rows;
    }

    private function stringifyValue(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }
        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }
        if (is_array($value)) {
            return collect($value)
                ->map(fn ($item) => is_scalar($item) ? (string) $item : json_encode($item))
                ->filter()
                ->implode(', ');
        }

        return (string) $value;
    }
}
