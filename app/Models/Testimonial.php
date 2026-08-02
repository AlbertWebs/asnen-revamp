<?php

namespace App\Models;

use App\Enums\ConsentStatus;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use Publishable;
    use SoftDeletes;

    protected $fillable = [
        'quote',
        'attribution_name',
        'attribution_role',
        'is_anonymous',
        'consent_status',
        'consent_expires_at',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
            'consent_status' => ConsentStatus::class,
            'consent_expires_at' => 'datetime',
        ];
    }
}
