<?php

declare(strict_types=1);

namespace App\Services\Ai\Schemas;

final class HotelSchema
{
    /** @var array<string, mixed> */
    public const SCHEMA = [
        'hotel_name' => 'string',
        'check_in' => 'string (YYYY-MM-DD)',
        'check_out' => 'string (YYYY-MM-DD)',
        'room_type' => 'string',
        'guests' => 'array of {name: string}',
        'confirmation_no' => 'string',
    ];
}
