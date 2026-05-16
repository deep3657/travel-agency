<?php

declare(strict_types=1);

namespace App\Models\Auth;

use App\Models\User;
use Database\Factories\Auth\StaffUserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Auth-only proxy of {@see User} that scopes to staff users (LLD §12).
 *
 * The `web` guard's provider points here so that an admin/agent user cannot
 * be accidentally authenticated against the customer guard, and customers
 * cannot log into /admin even if email collisions were ever introduced.
 */
final class StaffUser extends User
{
    protected $table = 'users';

    /**
     * Inherit the parent User's morph class so polymorphic relations
     * (notably Spatie's `model_has_roles` / `model_has_permissions`) match
     * regardless of whether the row was loaded through the User base class
     * (e.g. seeders) or through this proxy (e.g. via the `web` guard).
     */
    public function getMorphClass(): string
    {
        return User::class;
    }

    protected static function booted(): void
    {
        self::addGlobalScope(new class implements Scope
        {
            /**
             * @param  Builder<Model>  $builder
             */
            public function apply(Builder $builder, Model $model): void
            {
                $builder->where($model->getTable().'.user_type', User::TYPE_STAFF);
            }
        });
    }

    protected static function newFactory(): Factory
    {
        return StaffUserFactory::new();
    }
}
