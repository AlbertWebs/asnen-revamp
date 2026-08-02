<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsletterSubscriber extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'email',
        'name',
        'status',
        'source',
        'consent_at',
        'unsubscribed_at',
    ];

    protected function casts(): array
    {
        return [
            'consent_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }
}
