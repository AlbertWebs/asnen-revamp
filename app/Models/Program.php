<?php

namespace App\Models;

use App\Enums\VerificationStatus;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use HasSlug;
    use Publishable;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'body',
        'icon',
        'featured_image_id',
        'status',
        'published_at',
        'scheduled_at',
        'sort_order',
        'verification_status',
    ];

    protected function casts(): array
    {
        return [
            'verification_status' => VerificationStatus::class,
            'sort_order' => 'integer',
        ];
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'featured_image_id');
    }

    public function impactStories(): BelongsToMany
    {
        return $this->belongsToMany(ImpactStory::class, 'impact_story_program');
    }
}
