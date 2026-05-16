<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * Stateless service that owns all Customer write operations (LLD §8 style).
 * Controllers / Livewire components call this; they never touch Eloquent
 * directly for writes so audit hooks and ULID generation fire consistently.
 */
final class CustomerService
{
    /**
     * Create a new customer record.
     *
     * @param  array<string, mixed>  $data  Validated data from StoreCustomerRequest.
     */
    public function create(array $data, User $actor): Customer
    {
        $data['ulid'] ??= (string) Str::ulid();

        return Customer::query()->create($data);
    }

    /**
     * Update an existing customer.
     *
     * @param  array<string, mixed>  $data  Validated data from UpdateCustomerRequest.
     */
    public function update(Customer $customer, array $data, User $actor): Customer
    {
        // Unique-rule on phone/email uses `ignore:$customer->id` in the form
        // request so the current row's own values don't trigger a conflict.
        $customer->update($data);

        return $customer->refresh();
    }

    /**
     * Soft-delete a customer.  The row is preserved for audit trails and
     * so that historical bookings/enquiries remain intact (LLD §2.3).
     */
    public function delete(Customer $customer, User $actor): void
    {
        $customer->delete();
    }

    /**
     * Restore a previously soft-deleted customer.
     */
    public function restore(Customer $customer, User $actor): void
    {
        $customer->restore();
    }
}
