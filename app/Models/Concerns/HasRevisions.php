<?php

namespace App\Models\Concerns;

use App\Models\ContentRevision;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasRevisions
{
    public function revisions(): MorphMany
    {
        return $this->morphMany(ContentRevision::class, 'revisionable');
    }

    public function recordRevision(?User $user = null, ?string $note = null): ContentRevision
    {
        return $this->revisions()->create([
            'user_id' => $user?->id,
            'snapshot' => $this->toArray(),
            'note' => $note,
        ]);
    }
}
