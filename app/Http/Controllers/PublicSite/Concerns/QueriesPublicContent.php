<?php

namespace App\Http\Controllers\PublicSite\Concerns;

use App\Enums\VerificationStatus;
use App\Models\Announcement;
use App\Models\ImpactMetric;
use App\Models\Partner;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Builder;

trait QueriesPublicContent
{
    protected function verifiedPublishedMetrics(): Builder
    {
        return ImpactMetric::query()
            ->published()
            ->where('verification_status', VerificationStatus::Verified)
            ->orderByDesc('published_at');
    }

    protected function verifiedPublishedPartners(): Builder
    {
        return Partner::query()
            ->published()
            ->where('verification_status', VerificationStatus::Verified)
            ->orderBy('sort_order');
    }

    protected function publishedTeamMembers(): Builder
    {
        return TeamMember::query()
            ->published()
            ->orderBy('sort_order');
    }

    protected function activeAnnouncement(): ?Announcement
    {
        return Announcement::query()
            ->where('is_active', true)
            ->where(function (Builder $query) {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function (Builder $query) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->latest('starts_at')
            ->first();
    }
}
