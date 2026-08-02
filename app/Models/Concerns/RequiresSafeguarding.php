<?php

namespace App\Models\Concerns;

use App\Enums\SafeguardingStatus;

trait RequiresSafeguarding
{
    public function initializeRequiresSafeguarding(): void
    {
        $this->casts['requires_safeguarding'] = 'boolean';
        $this->casts['safeguarding_status'] = SafeguardingStatus::class;
    }

    public function canPublishSafely(): bool
    {
        if (! $this->requires_safeguarding) {
            return true;
        }

        return $this->safeguarding_status === SafeguardingStatus::Approved;
    }
}
