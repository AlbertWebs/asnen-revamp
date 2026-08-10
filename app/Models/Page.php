<?php

namespace App\Models;

use App\Enums\VerificationStatus;
use App\Models\Concerns\HasRevisions;
use App\Models\Concerns\HasSeoMeta;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use App\Models\Concerns\RequiresSafeguarding;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Page extends Model
{
    use HasRevisions;
    use HasSeoMeta;
    use HasSlug;
    use LogsActivity;
    use Publishable;
    use RequiresSafeguarding;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'template',
        'excerpt',
        'banner_image_ids',
        'status',
        'published_at',
        'scheduled_at',
        'unpublished_at',
        'timezone',
        'requires_safeguarding',
        'safeguarding_status',
        'verification_status',
        'author_id',
        'editor_notes',
    ];

    protected function casts(): array
    {
        return [
            'unpublished_at' => 'datetime',
            'verification_status' => VerificationStatus::class,
            'banner_image_ids' => 'array',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(PageBlock::class)->orderBy('sort_order');
    }

    /**
     * Ordered banner images for about / interior heroes.
     *
     * @return \Illuminate\Support\Collection<int, MediaAsset>
     */
    public function bannerImages()
    {
        $ids = collect($this->banner_image_ids ?? [])
            ->filter(fn ($id) => filled($id) && $id !== 'null')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return collect();
        }

        $byId = MediaAsset::query()
            ->whereIn('id', $ids->all())
            ->get()
            ->keyBy('id');

        return $ids
            ->map(fn (int $id) => $byId->get($id))
            ->filter()
            ->values();
    }
}
