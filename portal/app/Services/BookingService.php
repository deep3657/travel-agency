<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Str;

final class BookingService
{
    /** @param array<string, mixed> $data */
    public function create(array $data, User $actor): Booking
    {
        $data['ulid'] ??= (string) Str::ulid();

        return Booking::query()->create($data);
    }

    /** @param array<string, mixed> $data */
    public function update(Booking $b, array $data, User $actor): Booking
    {
        $b->update($data);

        return $b->refresh();
    }

    /** @param array<int, int> $passengerIds */
    public function attachPassengers(Booking $b, array $passengerIds, ?int $leadId): void
    {
        $pivot = [];
        foreach ($passengerIds as $pid) {
            $pivot[$pid] = ['is_lead' => $pid === $leadId];
        }
        $b->passengers()->sync($pivot);
    }

    public function copyPassengersFrom(Booking $source, Booking $target): int
    {
        $passengers = $source->passengers()->withPivot('is_lead')->get();
        $pivot = [];
        foreach ($passengers as $p) {
            $isLead = (bool) $p->getRelationValue('pivot')?->getAttribute('is_lead');
            $pivot[$p->id] = ['is_lead' => $isLead];
        }
        $target->passengers()->sync($pivot);

        return count($pivot);
    }
}
