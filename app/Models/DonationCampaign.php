<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonationCampaign extends Model
{
    use HasSlug;
    use Publishable;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'body',
        'goal_amount',
        'currency',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'goal_amount' => 'decimal:2',
        ];
    }
}
