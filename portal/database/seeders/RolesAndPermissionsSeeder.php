<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Seeds Spatie roles for staff users (LLD §3.1, §7.1).
 *
 * v1 ships only the two roles. Permissions remain implicit (admin sees
 * everything; agent sees everything except the financial / vendor / settings /
 * audit-log surfaces). This is enforced via Policies (LLD §7.2) rather than
 * granular Spatie permission rows. Rows are created idempotently so the
 * seeder is safe to re-run.
 */
class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::query()->firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::query()->firstOrCreate(['name' => 'agent', 'guard_name' => 'web']);
    }
}
