<?php

declare(strict_types=1);

namespace App\Support\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * Auto-populate created_by_id / updated_by_id from the authenticated user
 * (LLD §3 preamble + §4). Models also gain `creator()` and `editor()`
 * relationships for convenient eager-loading.
 *
 * @mixin Model
 */
trait TracksAuthor
{
    public static function bootTracksAuthor(): void
    {
        static::creating(function (Model $model): void {
            if (! Auth::hasUser()) {
                return;
            }

            $userId = Auth::id();

            if (empty($model->getAttribute('created_by_id'))) {
                $model->setAttribute('created_by_id', $userId);
            }

            if (empty($model->getAttribute('updated_by_id'))) {
                $model->setAttribute('updated_by_id', $userId);
            }
        });

        static::updating(function (Model $model): void {
            if (! Auth::hasUser()) {
                return;
            }

            $model->setAttribute('updated_by_id', Auth::id());
        });
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }
}
