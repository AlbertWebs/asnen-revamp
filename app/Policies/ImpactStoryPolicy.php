<?php

namespace App\Policies;

use App\Models\ImpactStory;
use App\Models\User;

class ImpactStoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('impact_stories.view');
    }

    public function view(User $user, ImpactStory $impactStory): bool
    {
        return $user->can('impact_stories.view');
    }

    public function create(User $user): bool
    {
        return $user->can('impact_stories.create');
    }

    public function update(User $user, ImpactStory $impactStory): bool
    {
        return $user->can('impact_stories.update');
    }

    public function delete(User $user, ImpactStory $impactStory): bool
    {
        return $user->can('impact_stories.delete');
    }

    public function publish(User $user, ImpactStory $impactStory): bool
    {
        return $user->can('impact_stories.publish');
    }

    public function approveSafeguarding(User $user, ImpactStory $impactStory): bool
    {
        return $user->can('safeguarding.approve');
    }
}
