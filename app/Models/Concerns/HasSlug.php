<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = static::generateUniqueSlug($model->getSlugSource());
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty($model->getSlugSourceAttributes()) && ! $model->isDirty('slug')) {
                $model->slug = static::generateUniqueSlug($model->getSlugSource(), $model->getKey());
            }
        });
    }

    protected function getSlugSourceAttributes(): array
    {
        if (isset($this->title)) {
            return ['title'];
        }

        return ['name'];
    }

    protected function getSlugSource(): string
    {
        return $this->title ?? $this->name ?? '';
    }

    protected static function generateUniqueSlug(string $source, mixed $ignoreId = null): string
    {
        $baseSlug = Str::slug($source);
        $slug = $baseSlug;
        $suffix = 2;

        while (static::slugExists($slug, $ignoreId)) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    protected static function slugExists(string $slug, mixed $ignoreId = null): bool
    {
        $query = static::query()->where('slug', $slug);

        if ($ignoreId !== null) {
            $query->whereKeyNot($ignoreId);
        }

        return $query->exists();
    }
}
