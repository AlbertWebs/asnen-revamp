<?php

namespace App\Models;

use App\Enums\VerificationStatus;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamMember extends Model
{
    use HasSlug;
    use Publishable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'title_role',
        'bio',
        'photo_id',
        'email',
        'linkedin_url',
        'sort_order',
        'is_board',
        'verification_status',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'verification_status' => VerificationStatus::class,
            'is_board' => 'boolean',
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

    public function photo(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'photo_id');
    }
}
