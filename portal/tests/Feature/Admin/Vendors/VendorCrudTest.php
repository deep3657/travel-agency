<?php

declare(strict_types=1);

namespace Tests\Feature\Admin\Vendors;

use App\Livewire\Admin\Vendors\VendorForm;
use App\Livewire\Admin\Vendors\VendorsIndex;
use App\Models\Auth\StaffUser;
use App\Models\User;
use App\Models\Vendor;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * M3 — Vendor CRUD, soft-delete, admin-only RBAC.
 */
class VendorCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    private function admin(): User
    {
        $user = StaffUser::factory()->create();
        $user->assignRole('admin');

        return $user;
    }

    private function agent(): User
    {
        $user = StaffUser::factory()->create();
        $user->assignRole('agent');

        return $user;
    }

    // ── LIST ─────────────────────────────────────────────────────────────────

    public function test_admin_can_view_vendor_index(): void
    {
        $this->actingAs($this->admin());
        Vendor::factory()->create(['name' => 'IndiGo Airlines']);

        Livewire::test(VendorsIndex::class)
            ->assertSee('IndiGo Airlines');
    }

    public function test_agent_cannot_view_vendor_index(): void
    {
        $this->actingAs($this->agent());

        Livewire::test(VendorsIndex::class)
            ->assertForbidden();
    }

    public function test_unauthenticated_redirected_from_vendor_index(): void
    {
        $this->get(route('admin.vendors.index'))
            ->assertRedirect(route('login'));
    }

    // ── SEARCH ───────────────────────────────────────────────────────────────

    public function test_search_filters_vendors_by_name(): void
    {
        $this->actingAs($this->admin());
        Vendor::factory()->create(['name' => 'Air India', 'code' => 'AI-001']);
        Vendor::factory()->create(['name' => 'SpiceJet', 'code' => 'SJ-001']);

        Livewire::test(VendorsIndex::class)
            ->set('search', 'Air India')
            ->assertSee('Air India')
            ->assertDontSee('SpiceJet');
    }

    // ── CREATE ────────────────────────────────────────────────────────────────

    public function test_admin_can_create_vendor(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(VendorForm::class)
            ->set('name', 'Vistara Airlines')
            ->set('code', 'UK-001')
            ->set('payment_terms_days', 30)
            ->call('save');

        $this->assertDatabaseHas('vendors', ['name' => 'Vistara Airlines', 'code' => 'UK-001']);
    }

    public function test_agent_cannot_access_vendor_create_form(): void
    {
        $this->actingAs($this->agent());

        Livewire::test(VendorForm::class)
            ->assertForbidden();
    }

    public function test_invalid_gstin_is_rejected(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(VendorForm::class)
            ->set('name', 'Bad GST Co')
            ->set('gstin', 'NOT-A-GSTIN')
            ->call('save')
            ->assertHasErrors(['gstin']);
    }

    public function test_valid_gstin_is_accepted(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(VendorForm::class)
            ->set('name', 'GST Vendor Ltd')
            ->set('gstin', '27ABCDE1234F1Z5')
            ->call('save')
            ->assertHasNoErrors(['gstin']);

        $this->assertDatabaseHas('vendors', ['gstin' => '27ABCDE1234F1Z5']);
    }

    // ── UPDATE ────────────────────────────────────────────────────────────────

    public function test_admin_can_update_vendor(): void
    {
        $this->actingAs($this->admin());
        $vendor = Vendor::factory()->create();

        Livewire::test(VendorForm::class, ['ulid' => $vendor->ulid])
            ->set('name', 'Updated Vendor Name')
            ->call('save');

        $this->assertDatabaseHas('vendors', ['id' => $vendor->id, 'name' => 'Updated Vendor Name']);
    }

    public function test_agent_cannot_access_vendor_edit_form(): void
    {
        $this->actingAs($this->agent());
        $vendor = Vendor::factory()->create();

        Livewire::test(VendorForm::class, ['ulid' => $vendor->ulid])
            ->assertForbidden();
    }

    // ── SOFT-DELETE ───────────────────────────────────────────────────────────

    public function test_admin_can_soft_delete_vendor(): void
    {
        $this->actingAs($this->admin());
        $vendor = Vendor::factory()->create();

        Livewire::test(VendorsIndex::class)
            ->call('deleteVendor', $vendor->id);

        $this->assertSoftDeleted('vendors', ['id' => $vendor->id]);
    }

    public function test_soft_deleted_vendor_hidden_by_default(): void
    {
        $this->actingAs($this->admin());
        $vendor = Vendor::factory()->create(['name' => 'Archived Vendor']);
        $vendor->delete();

        Livewire::test(VendorsIndex::class)
            ->assertDontSee('Archived Vendor');
    }

    public function test_soft_deleted_vendor_visible_with_show_deleted(): void
    {
        $this->actingAs($this->admin());
        $vendor = Vendor::factory()->create(['name' => 'Deleted Vendor Co']);
        $vendor->delete();

        Livewire::test(VendorsIndex::class)
            ->set('showDeleted', true)
            ->assertSee('Deleted Vendor Co');
    }

    public function test_admin_can_restore_vendor(): void
    {
        $this->actingAs($this->admin());
        $vendor = Vendor::factory()->create();
        $vendor->delete();

        Livewire::test(VendorsIndex::class)
            ->set('showDeleted', true)
            ->call('restoreVendor', $vendor->id);

        $this->assertNotSoftDeleted('vendors', ['id' => $vendor->id]);
    }

    // ── Nav access guard ──────────────────────────────────────────────────────

    public function test_agent_gets_403_on_vendor_index_route(): void
    {
        $this->actingAs($this->agent())
            ->get(route('admin.vendors.index'))
            ->assertForbidden();
    }
}
