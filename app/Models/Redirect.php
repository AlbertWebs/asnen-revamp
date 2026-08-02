<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'from_path',
        'to_path',
        'status_code',
        'is_active',
        'hits',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'hits' => 'integer',
            'status_code' => 'integer',
        ];
    }
}
