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
}
