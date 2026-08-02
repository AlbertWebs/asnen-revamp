<?php

namespace App\Models;

use App\Enums\VerificationStatus;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasSlug;
    use Publishable;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'summary',
        'body',
        'venue',
        'is_online',
        'online_url',
        'starts_at',
        'ends_at',
        'timezone',
        'capacity',
        'registration_opens_at',
        'registration_closes_at',
        'featured_image_id',
        'status',
        'published_at',
        'verification_status',
    ];

    protected function casts(): array
    {
        return [
            'is_online' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'registration_opens_at' => 'datetime',
            'registration_closes_at' => 'datetime',
            'verification_status' => VerificationStatus::class,
            'capacity' => 'integer',
        ];
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'featured_image_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function isUpcoming(): bool
    {
        return $this->starts_at !== null && $this->starts_at->isFuture();
    }
}
