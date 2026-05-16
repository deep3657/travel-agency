<?php

declare(strict_types=1);

namespace App\Exceptions;

final class BookingRefGenerationException extends AppException
{
    public function code(): string
    {
        return 'BOOKING_REF_FAIL';
    }
}
