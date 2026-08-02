<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageBlock extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'page_id',
        'type',
        'sort_order',
        'is_visible',
        'content',
        'settings',
        'scheduled_from',
        'scheduled_until',
        'anchor_id',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'settings' => 'array',
            'is_visible' => 'boolean',
            'sort_order' => 'integer',
            'scheduled_from' => 'datetime',
            'scheduled_until' => 'datetime',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
