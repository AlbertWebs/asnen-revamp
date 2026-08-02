<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormDefinition extends Model
{
    use HasSlug;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'fields',
        'success_message',
        'notify_emails',
        'is_active',
        'retention_days',
    ];

    protected function casts(): array
    {
        return [
            'fields' => 'array',
            'notify_emails' => 'array',
            'is_active' => 'boolean',
            'retention_days' => 'integer',
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

    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }
}
