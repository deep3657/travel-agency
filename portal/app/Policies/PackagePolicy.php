<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Package;
use App\Models\User;

class PackagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    public function view(User $user, Package $package): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    public function update(User $user, Package $package): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    public function delete(User $user, Package $package): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, Package $package): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Package $package): bool
    {
        return false;
    }
}
