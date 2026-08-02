<?php

namespace App\Models;

use App\Enums\VerificationStatus;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImpactMetric extends Model
{
    use Publishable;
    use SoftDeletes;

    protected $fillable = [
        'label',
        'value',
        'numeric_value',
        'unit',
        'qualifier',
        'source_label',
        'as_of_date',
        'region',
        'program_id',
        'verification_status',
        'status',
        'published_at',
        'public_label',
    ];

    protected function casts(): array
    {
        return [
            'verification_status' => VerificationStatus::class,
            'numeric_value' => 'decimal:4',
            'as_of_date' => 'date',
        ];
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
