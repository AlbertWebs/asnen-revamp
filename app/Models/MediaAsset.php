<?php

namespace App\Models;

use App\Enums\ConsentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MediaAsset extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'disk',
        'path',
        'filename',
        'mime',
        'size',
        'content_hash',
        'width',
        'height',
        'alt',
        'caption',
        'credit',
        'copyright',
        'folder',
        'tags',
        'focal_point_x',
        'focal_point_y',
        'is_private',
        'consent_status',
        'consent_notes',
        'consented_at',
        'consented_by',
        'variants',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'variants' => 'array',
            'is_private' => 'boolean',
            'consent_status' => ConsentStatus::class,
            'consented_at' => 'datetime',
            'size' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
            'focal_point_x' => 'decimal:4',
            'focal_point_y' => 'decimal:4',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (MediaAsset $asset) {
            if (empty($asset->uuid)) {
                $asset->uuid = (string) Str::uuid();
            }
        });
    }

    public function consentedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'consented_by');
    }

    public function usages(): HasMany
    {
        return $this->hasMany(MediaUsage::class);
    }

    public function publicUrl(): ?string
    {
        if ($this->is_private || empty($this->path) || $this->path === '0') {
            return null;
        }

        return \Illuminate\Support\Facades\Storage::disk($this->disk)->url($this->path);
    }
}
