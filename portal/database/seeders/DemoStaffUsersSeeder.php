<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Demo accounts used in M1's exit-criteria walk-through.
 *
 * Both users have email_verified_at set so they can land directly on /admin
 * without going through the verification flow during local demos. Real
 * production users go through Breeze's email-verification Mailable.
 *
 * NEVER seed these in production (this seeder only runs from
 * `php artisan db:seed --class=DemoStaffUsersSeeder` or via DatabaseSeeder
 * when APP_ENV !== 'production').
 */
class DemoStaffUsersSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command->warn('Skipping DemoStaffUsersSeeder in production.');

            return;
        }

        // The default password is fine for local dev demos because APP_DEBUG
        // is true and the app isn't reachable from the public internet there.
        // The seeder is gated above so this never runs in production.
        $defaultPassword = 'password';

        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@maruti.test'],
            [
                'name' => 'Maruti Admin',
                'phone' => '+91 9000000001',
                'password' => $defaultPassword,
                'user_type' => User::TYPE_STAFF,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );
        if (! $admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        $agent = User::query()->firstOrCreate(
            ['email' => 'agent@maruti.test'],
            [
                'name' => 'Maruti Agent',
                'phone' => '+91 9000000002',
                'password' => $defaultPassword,
                'user_type' => User::TYPE_STAFF,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );
        if (! $agent->hasRole('agent')) {
            $agent->assignRole('agent');
        }
    }
}
