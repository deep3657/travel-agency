<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\AgencySetting;
use App\Models\Auth\StaffUser;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_admin_can_load_settings_page(): void
    {
        $admin = StaffUser::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/admin/settings');

        $response->assertOk();
        $response->assertSee('Agency settings');
    }

    public function test_agent_is_forbidden_from_settings(): void
    {
        $agent = StaffUser::factory()->create();
        $agent->assignRole('agent');

        $response = $this->actingAs($agent)->get('/admin/settings');

        $response->assertForbidden();
    }

    public function test_unauthenticated_redirects_to_login(): void
    {
        $response = $this->get('/admin/settings');

        $response->assertRedirect('/login');
    }

    public function test_unverified_email_redirects_to_verification_notice(): void
    {
        $admin = StaffUser::factory()->unverified()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/admin/settings');

        $response->assertRedirect('/verify-email');
    }

    public function test_singleton_returns_same_row(): void
    {
        $a = AgencySetting::singleton();
        $b = AgencySetting::singleton();

        $this->assertSame($a->id, $b->id);
        $this->assertSame(AgencySetting::SINGLETON_ID, $a->id);
        $this->assertSame(1, AgencySetting::query()->count());
    }
}
