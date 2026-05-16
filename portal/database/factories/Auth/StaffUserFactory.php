<?php

declare(strict_types=1);

namespace Database\Factories\Auth;

use App\Models\Auth\StaffUser;
use App\Models\User;
use Database\Factories\UserFactory;

class StaffUserFactory extends UserFactory
{
    /** @var class-string<StaffUser> */
    protected $model = StaffUser::class;

    public function definition(): array
    {
        return array_merge(parent::definition(), [
            'user_type' => User::TYPE_STAFF,
        ]);
    }
}
