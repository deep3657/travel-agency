<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\SettingsForm;
use App\Models\AgencySetting;
use App\Models\Auth\StaffUser;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class SettingsFormSaveTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_admin_save_persists_and_writes_audit_row(): void
    {
        $admin = StaffUser::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        // Touch the singleton so its initial state is logged-baseline.
        AgencySetting::singleton();

        Livewire::test(SettingsForm::class)
            ->set('agency_name', 'Maruti Travels')
            ->set('gstin', '27ABCDE1234F1Z5')
            ->set('state', 'Maharashtra')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('saved', true);

        $s = AgencySetting::singleton()->refresh();
        $this->assertSame('27ABCDE1234F1Z5', $s->gstin);
        $this->assertSame('Maharashtra', $s->state);
        $this->assertSame($admin->id, $s->updated_by_id);

        // Audit log captures the change with the admin as causer and only
        // dirty fields in the properties payload.
        $activity = Activity::query()
            ->where('subject_type', AgencySetting::class)
            ->where('subject_id', $s->id)
            ->where('event', 'updated')
            ->latest('id')
            ->first();

        $this->assertNotNull($activity, 'Expected an activity log row for the updated setting.');
        $this->assertSame($admin->id, (int) $activity->causer_id);
        // StaffUser overrides getMorphClass() to return the User parent so
        // that polymorphic relationships (Spatie roles, activity log causer)
        // resolve consistently across the User and StaffUser/CustomerUser
        // proxy classes — see App\Models\Auth\StaffUser::getMorphClass().
        $this->assertEquals(User::class, $activity->causer_type);
        $this->assertArrayHasKey('attributes', $activity->properties->toArray());
        $this->assertSame('Maharashtra', $activity->properties['attributes']['state'] ?? null);
        $this->assertSame('27ABCDE1234F1Z5', $activity->properties['attributes']['gstin'] ?? null);
    }

    public function test_invalid_gstin_is_rejected(): void
    {
        $admin = StaffUser::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        Livewire::test(SettingsForm::class)
            ->set('agency_name', 'Maruti Travels')
            ->set('gstin', 'NOT_A_VALID_GSTIN')
            ->call('save')
            ->assertHasErrors(['gstin']);
    }

    public function test_agent_cannot_mount_settings_form(): void
    {
        $agent = StaffUser::factory()->create();
        $agent->assignRole('agent');
        $this->actingAs($agent);

        Livewire::test(SettingsForm::class)
            ->assertStatus(403);
    }
}
