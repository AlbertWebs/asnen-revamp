<?php

namespace App\Models;

use App\Enums\VerificationStatus;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publication extends Model
{
    use HasSlug;
    use Publishable;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'year',
        'abstract',
        'authors',
        'version',
        'cover_id',
        'file_id',
        'accessible_file_id',
        'download_count',
        'status',
        'published_at',
        'verification_status',
    ];

    protected function casts(): array
    {
        return [
            'authors' => 'array',
            'year' => 'integer',
            'download_count' => 'integer',
            'verification_status' => VerificationStatus::class,
        ];
    }

    public function cover(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'cover_id');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'file_id');
    }

    public function accessibleFile(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'accessible_file_id');
    }

    public function categoryLabel(): string
    {
        return match ($this->category) {
            'annual_report' => 'Annual report',
            'conference_report' => 'Conference report',
            'impact_report' => 'Impact report',
            'report' => 'Report',
            'toolkit' => 'Toolkit',
            'guide' => 'Guide',
            default => 'Publication',
        };
    }

    public function fileSizeLabel(): ?string
    {
        $bytes = $this->file?->size;
        if (! $bytes) {
            return null;
        }

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1).' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 0).' KB';
        }

        return $bytes.' B';
    }
}
