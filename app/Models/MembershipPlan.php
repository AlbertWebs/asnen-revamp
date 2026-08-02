<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembershipPlan extends Model
{
    use HasSlug;
    use Publishable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'summary',
        'benefits',
        'eligibility',
        'sort_order',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'benefits' => 'array',
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
}
