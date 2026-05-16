<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent() || $user->isCustomer();
    }

    public function view(User $user, Booking $booking): bool
    {
        if ($user->isCustomer()) {
            return $user->customer_id === $booking->customer_id;
        }

        return $user->isAdmin() || $user->isAgent();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    public function update(User $user, Booking $booking): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    public function seeFinancials(User $user): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Booking $booking): bool
    {
        return $user->isAdmin();
    }
}
