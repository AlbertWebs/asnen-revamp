<?php

namespace App\Models;

use App\Enums\VerificationStatus;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partner extends Model
{
    use HasSlug;
    use Publishable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'url',
        'logo_id',
        'category',
        'partnership_start',
        'partnership_end',
        'sort_order',
        'verification_status',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'verification_status' => VerificationStatus::class,
            'partnership_start' => 'date',
            'partnership_end' => 'date',
            'sort_order' => 'integer',
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

    public function logo(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'logo_id');
    }

    public function impactStories(): BelongsToMany
    {
        return $this->belongsToMany(ImpactStory::class, 'impact_story_partner');
    }
}
