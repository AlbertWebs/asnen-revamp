<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use App\Models\Concerns\RequiresSafeguarding;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use HasSlug;
    use Publishable;
    use RequiresSafeguarding;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'location',
        'gallery_date',
        'status',
        'published_at',
        'requires_safeguarding',
        'safeguarding_status',
    ];

    protected function casts(): array
    {
        return [
            'gallery_date' => 'date',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(GalleryItem::class)->orderBy('sort_order');
    }

    public function coverItem(): HasOne
    {
        return $this->hasOne(GalleryItem::class)->ofMany('sort_order', 'min');
    }

    public function impactStories(): HasMany
    {
        return $this->hasMany(ImpactStory::class);
    }

    public function isGeneralGallery(): bool
    {
        return $this->slug === 'general-gallery';
    }

    public static function orderedForPicker()
    {
        return static::query()
            ->orderByRaw("CASE WHEN slug = 'general-gallery' THEN 1 ELSE 0 END")
            ->orderBy('title')
            ->get(['id', 'title', 'slug']);
    }
}
