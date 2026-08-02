<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoryOutcome extends Model
{
    protected $fillable = [
        'impact_story_id',
        'label',
        'value',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function impactStory(): BelongsTo
    {
        return $this->belongsTo(ImpactStory::class);
    }
}
