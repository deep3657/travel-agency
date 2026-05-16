<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ChangeRequest;
use App\Models\User;

class ChangeRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    public function view(User $user, ChangeRequest $changeRequest): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent() || $user->isCustomer();
    }

    public function update(User $user, ChangeRequest $changeRequest): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    public function complete(User $user, ChangeRequest $changeRequest): bool
    {
        return $user->isAdmin();
    }
}
