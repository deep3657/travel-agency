<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

/**
 * Base class for app-specific exceptions (LLD §19). Every subclass exposes a
 * stable string `code()` that propagates into structured logs.
 */
abstract class AppException extends RuntimeException
{
    /**
     * Stable string code used in logs/metrics (NOT the parent::$code int).
     */
    abstract public function code(): string;
}
