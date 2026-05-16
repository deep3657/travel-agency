<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Vendor;

/**
 * Vendor master is Admin-only (LLD §7.1).
 * Agents cannot view, create, edit, or delete vendors.
 */
class VendorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Vendor $vendor): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Vendor $vendor): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Vendor $vendor): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, Vendor $vendor): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Vendor $vendor): bool
    {
        return false;
    }
}
