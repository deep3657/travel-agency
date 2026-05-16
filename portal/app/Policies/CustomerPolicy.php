<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

/**
 * Authorization policy for Customer records (LLD §7.1, §7.2).
 *
 * RBAC matrix:
 *   viewAny / view  — Admin ✓  Agent ✓  Customer: own record only
 *   create / update — Admin ✓  Agent ✓  Customer: own profile (M5)
 *   delete / restore — Admin ✓  Agent ✗  Customer ✗
 */
class CustomerPolicy
{
    /**
     * List page — any staff member may view the customer index.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    /**
     * Detail / show — staff can view all; customers can only view themselves.
     */
    public function view(User $user, Customer $customer): bool
    {
        if ($user->isCustomer()) {
            return $user->customer_id === $customer->id;
        }

        return $user->isAdmin() || $user->isAgent();
    }

    /**
     * Create — any staff member may add customers.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    /**
     * Update — staff only; customers can edit their own profile (M5 wires
     * the customer-portal side).
     */
    public function update(User $user, Customer $customer): bool
    {
        if ($user->isCustomer()) {
            return $user->customer_id === $customer->id;
        }

        return $user->isAdmin() || $user->isAgent();
    }

    /**
     * Soft-delete — admin only (LLD §7.1 — agents cannot delete customers).
     */
    public function delete(User $user, Customer $customer): bool
    {
        return $user->isAdmin();
    }

    /**
     * Restore a soft-deleted customer — admin only.
     */
    public function restore(User $user, Customer $customer): bool
    {
        return $user->isAdmin();
    }

    /**
     * Force-delete is never exposed in the application UI.
     */
    public function forceDelete(User $user, Customer $customer): bool
    {
        return false;
    }
}
