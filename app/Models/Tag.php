<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    use HasSlug;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
    ];

    protected function getSlugSource(): string
    {
        return $this->name;
    }

    protected function getSlugSourceAttributes(): array
    {
        return ['name'];
    }

    public function articles(): MorphToMany
    {
        return $this->morphedByMany(Article::class, 'taggable');
    }
}
