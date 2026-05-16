<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Trip;
use App\Models\User;

class TripPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent() || $user->isCustomer();
    }

    public function view(User $user, Trip $trip): bool
    {
        if ($user->isCustomer()) {
            return $user->customer_id === $trip->customer_id;
        }

        return $user->isAdmin() || $user->isAgent();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    public function update(User $user, Trip $trip): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    public function delete(User $user, Trip $trip): bool
    {
        return $user->isAdmin();
    }
}
