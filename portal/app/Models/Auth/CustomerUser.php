<?php

declare(strict_types=1);

namespace App\Models\Auth;

use App\Models\User;
use Database\Factories\Auth\CustomerUserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Auth-only proxy of {@see User} that scopes to customer-portal users.
 *
 * Used by the `customer` guard configured in config/auth.php. The customer
 * signup/login UI ships in M5; the model is wired up early so the guard
 * config is stable.
 */
final class CustomerUser extends User
{
    protected $table = 'users';

    /**
     * Same morph alias as {@see StaffUser::getMorphClass()} — see that method
     * for the rationale.
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
                $builder->where($model->getTable().'.user_type', User::TYPE_CUSTOMER);
            }
        });
    }

    protected static function newFactory(): Factory
    {
        return CustomerUserFactory::new();
    }
}
