<?php

declare(strict_types=1);

namespace App\Services\Ai\Schemas;

final class FlightSchema
{
    /** @var array<string, mixed> */
    public const SCHEMA = [
        'airline' => 'string',
        'flight_no' => 'string',
        'origin' => 'string',
        'destination' => 'string',
        'departure_datetime' => 'string (ISO 8601)',
        'arrival_datetime' => 'string (ISO 8601)',
        'class' => 'string',
        'pnr' => 'string',
        'passengers' => 'array of {name: string, seat: string}',
    ];
}
