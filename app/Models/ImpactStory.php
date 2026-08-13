<?php

namespace App\Models;

use App\Enums\VerificationStatus;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use App\Models\Concerns\RequiresSafeguarding;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class ImpactStory extends Model
{
    use HasSlug;
    use LogsActivity;
    use Publishable;
    use RequiresSafeguarding;
    use SoftDeletes;

    public const KOMOLION_SLUG = 'komolion-2023-disability-assessment-medical-camp';

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'body',
        'location',
        'story_date',
        'featured_image_id',
        'gallery_id',
        'status',
        'published_at',
        'scheduled_at',
        'requires_safeguarding',
        'safeguarding_status',
        'verification_status',
        'challenges',
        'learnings',
        'next_steps',
    ];

    protected function casts(): array
    {
        return [
            'story_date' => 'date',
            'verification_status' => VerificationStatus::class,
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'featured_image_id');
    }

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }

    public function outcomes(): HasMany
    {
        return $this->hasMany(StoryOutcome::class)->orderBy('sort_order');
    }

    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(Partner::class, 'impact_story_partner');
    }

    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'impact_story_program');
    }

    public function publicUrl(): string
    {
        return route('site.impact.stories.show', $this->slug);
    }
}
