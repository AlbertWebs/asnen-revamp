<?php

namespace App\Models;

use App\Enums\VerificationStatus;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Webinar extends Model
{
    use HasSlug;
    use Publishable;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'body',
        'held_at',
        'moderator',
        'participant_count',
        'recording_url',
        'transcript',
        'featured_image_id',
        'status',
        'published_at',
        'verification_status',
    ];

    protected function casts(): array
    {
        return [
            'held_at' => 'datetime',
            'participant_count' => 'integer',
            'verification_status' => VerificationStatus::class,
        ];
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'featured_image_id');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(WebinarResource::class)->orderBy('sort_order');
    }
}
