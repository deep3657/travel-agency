<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Enquiry;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Str;

final class TripService
{
    /** @param array<string, mixed> $data */
    public function create(array $data, User $actor): Trip
    {
        $data['ulid'] ??= (string) Str::ulid();

        return Trip::query()->create($data);
    }

    /** @param array<string, mixed> $data */
    public function update(Trip $trip, array $data, User $actor): Trip
    {
        $trip->update($data);

        return $trip->refresh();
    }

    /** @param array<string, mixed> $data */
    public function convertFromEnquiry(Enquiry $e, array $data, User $actor): Trip
    {
        $data['customer_id'] ??= $e->customer_id;
        $data['ulid'] ??= (string) Str::ulid();

        $trip = Trip::query()->create($data);

        $e->update(['converted_to_trip_id' => $trip->id]);

        return $trip;
    }
}
