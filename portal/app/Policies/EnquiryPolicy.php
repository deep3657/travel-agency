<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Enquiry;
use App\Models\User;

class EnquiryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    public function view(User $user, Enquiry $enquiry): bool
    {
        if ($user->isCustomer()) {
            return $user->customer_id === $enquiry->customer_id;
        }

        return $user->isAdmin() || $user->isAgent();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent() || $user->isCustomer();
    }

    public function update(User $user, Enquiry $enquiry): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    public function close(User $user, Enquiry $enquiry): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Enquiry $enquiry): bool
    {
        return $user->isAdmin();
    }
}
