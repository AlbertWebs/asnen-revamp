<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventRegistration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'event_id',
        'name',
        'email',
        'phone',
        'organization',
        'notes',
        'status',
        'consent_marketing',
        'ip',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'consent_marketing' => 'boolean',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
