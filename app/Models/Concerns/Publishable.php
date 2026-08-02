<?php

namespace App\Models\Concerns;

use App\Enums\PublishStatus;
use Illuminate\Database\Eloquent\Builder;

trait Publishable
{
    public function initializePublishable(): void
    {
        $this->casts['status'] = PublishStatus::class;
        $this->casts['published_at'] = 'datetime';
        $this->casts['scheduled_at'] = 'datetime';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', PublishStatus::Published)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeScheduledReady(Builder $query): Builder
    {
        return $query
            ->where('status', PublishStatus::Scheduled)
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now());
    }

    public function isPublished(): bool
    {
        return $this->status === PublishStatus::Published
            && $this->published_at !== null
            && $this->published_at->lte(now());
    }

    public function publish(): bool
    {
        if (method_exists($this, 'canPublishSafely') && ! $this->canPublishSafely()) {
            return false;
        }

        return $this->update([
            'status' => PublishStatus::Published,
            'published_at' => now(),
        ]);
    }

    public function unpublish(): bool
    {
        $attributes = [
            'status' => PublishStatus::Draft,
            'published_at' => null,
        ];

        if ($this->isFillable('unpublished_at')) {
            $attributes['unpublished_at'] = now();
        }

        return $this->update($attributes);
    }

    public function archive(): bool
    {
        return $this->update([
            'status' => PublishStatus::Archived,
        ]);
    }
}
