<?php

declare(strict_types=1);

namespace App\Support\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Adds a 26-char ULID to the model on creation (LLD §2.2 — ULIDs in URLs,
 * auto-increment bigint as PK, both stored on row).
 *
 * Also overrides `getRouteKeyName()` so that implicit route–model binding
 * (e.g. `Route::post('/vouchers/{booking}', ...)` with a `Booking $booking`
 * controller parameter) resolves models by their ULID rather than the
 * internal auto-increment id. Internal ids are never exposed in URLs.
 *
 * The model's table must have a `ulid CHAR(26) NOT NULL UNIQUE` column.
 *
 * @mixin Model
 */
trait HasUlid
{
    public static function bootHasUlid(): void
    {
        static::creating(function (Model $model): void {
            if (empty($model->getAttribute('ulid'))) {
                $model->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }
}
