<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Quotation;
use App\Models\User;

class QuotationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent() || $user->isCustomer();
    }

    public function view(User $user, Quotation $quotation): bool
    {
        if ($user->isCustomer()) {
            return $user->customer_id === $quotation->trip->customer_id;
        }

        return $user->isAdmin() || $user->isAgent();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    public function update(User $user, Quotation $quotation): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    public function seeFinancials(User $user): bool
    {
        return $user->isAdmin();
    }
}
