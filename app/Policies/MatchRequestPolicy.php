<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\MatchRequest;
use App\Models\User;

class MatchRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view match requests
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MatchRequest $matchRequest): bool
    {
        return true; // All authenticated users can view match request details
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // User must be owner or captain of at least one team
        return $user->ownsTeams() || $user->isCaptainOfTeams();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MatchRequest $matchRequest): bool
    {
        $team = $matchRequest->team;

        return $team->isOwnedBy($user) || $team->isCaptain($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MatchRequest $matchRequest): bool
    {
        $team = $matchRequest->team;

        return $team->isOwnedBy($user) || $team->isCaptain($user);
    }

    /**
     * Determine whether the user can accept applications for the match request.
     */
    public function acceptApplication(User $user, MatchRequest $matchRequest): bool
    {
        $team = $matchRequest->team;

        return $team->isOwnedBy($user) || $team->isCaptain($user);
    }

    /**
     * Determine whether the user can apply to the match request.
     */
    public function apply(User $user, MatchRequest $matchRequest): bool
    {
        // User must be owner or captain of at least one team
        if (!$user->ownsTeams() && !$user->isCaptainOfTeams()) {
            return false;
        }

        // User cannot apply to their own team's match request
        $team = $matchRequest->team;
        if ($team->isOwnedBy($user) || $team->isCaptain($user)) {
            return false;
        }

        return true;
    }
}
