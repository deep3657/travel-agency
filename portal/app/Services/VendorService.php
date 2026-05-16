<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Str;

/**
 * Stateless service for Vendor write operations (LLD §8 pattern).
 */
final class VendorService
{
    /** @param array<string, mixed> $data */
    public function create(array $data, User $actor): Vendor
    {
        $data['ulid'] ??= (string) Str::ulid();

        return Vendor::query()->create($data);
    }

    /** @param array<string, mixed> $data */
    public function update(Vendor $vendor, array $data, User $actor): Vendor
    {
        $vendor->update($data);

        return $vendor->refresh();
    }

    public function delete(Vendor $vendor, User $actor): void
    {
        $vendor->delete();
    }

    public function restore(Vendor $vendor, User $actor): void
    {
        $vendor->restore();
    }
}
