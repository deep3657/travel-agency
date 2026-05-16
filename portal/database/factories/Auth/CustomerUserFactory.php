<?php

declare(strict_types=1);

namespace Database\Factories\Auth;

use App\Models\Auth\CustomerUser;
use App\Models\User;
use Database\Factories\UserFactory;

class CustomerUserFactory extends UserFactory
{
    /** @var class-string<CustomerUser> */
    protected $model = CustomerUser::class;

    public function definition(): array
    {
        return array_merge(parent::definition(), [
            'user_type' => User::TYPE_CUSTOMER,
        ]);
    }
}
