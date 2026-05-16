<?php

declare(strict_types=1);

namespace Tests\Feature\Admin\Customers;

use App\Livewire\Admin\Customers\CustomerForm;
use App\Livewire\Admin\Customers\CustomersIndex;
use App\Models\Auth\StaffUser;
use App\Models\Customer;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * M2 — Customer CRUD, GSTIN validation, soft-delete, search, RBAC.
 */
class CustomerCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    // ── helpers ──────────────────────────────────────────────────────────────

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

    // ── LIST / INDEX ──────────────────────────────────────────────────────────

    public function test_admin_can_view_customers_index(): void
    {
        $this->actingAs($this->admin());
        Customer::factory()->count(3)->create();

        Livewire::test(CustomersIndex::class)
            ->assertSee(Customer::first()->name);
    }

    public function test_agent_can_view_customers_index(): void
    {
        $this->actingAs($this->agent());
        Customer::factory()->create(['name' => 'Ravi Kumar']);

        Livewire::test(CustomersIndex::class)
            ->assertSee('Ravi Kumar');
    }

    public function test_unauthenticated_redirected_from_index(): void
    {
        $this->get(route('admin.customers.index'))
            ->assertRedirect(route('login'));
    }

    // ── SEARCH ───────────────────────────────────────────────────────────────

    public function test_search_filters_by_name(): void
    {
        $this->actingAs($this->admin());
        Customer::factory()->create(['name' => 'Shalini Rao', 'phone' => '+91 9000000001', 'email' => 'shalini@example.com']);
        Customer::factory()->create(['name' => 'Vikram Singh', 'phone' => '+91 9000000002', 'email' => 'vikram@example.com']);

        Livewire::test(CustomersIndex::class)
            ->set('search', 'Shalini')
            ->assertSee('Shalini Rao')
            ->assertDontSee('Vikram Singh');
    }

    public function test_search_filters_by_phone(): void
    {
        $this->actingAs($this->admin());
        Customer::factory()->create(['name' => 'Priya', 'phone' => '+91 9876543210', 'email' => 'priya@example.com']);
        Customer::factory()->create(['name' => 'Anand', 'phone' => '+91 9123456789', 'email' => 'anand@example.com']);

        Livewire::test(CustomersIndex::class)
            ->set('search', '9876543210')
            ->assertSee('Priya')
            ->assertDontSee('Anand');
    }

    // ── CREATE ────────────────────────────────────────────────────────────────

    public function test_admin_can_create_customer(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);

        Livewire::test(CustomerForm::class)
            ->set('name', 'Deepa Nair')
            ->set('phone', '+91 9500000001')
            ->set('email', 'deepa@example.com')
            ->set('country', 'India')
            ->call('save');

        $this->assertDatabaseHas('customers', ['email' => 'deepa@example.com']);
    }

    public function test_agent_can_create_customer(): void
    {
        $this->actingAs($this->agent());

        Livewire::test(CustomerForm::class)
            ->set('name', 'Arjun Mehta')
            ->set('phone', '+91 9500000002')
            ->set('email', 'arjun@example.com')
            ->set('country', 'India')
            ->call('save');

        $this->assertDatabaseHas('customers', ['email' => 'arjun@example.com']);
    }

    // ── GSTIN VALIDATION ─────────────────────────────────────────────────────

    public function test_invalid_gstin_is_rejected(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CustomerForm::class)
            ->set('name', 'Test Co')
            ->set('phone', '+91 9500000003')
            ->set('email', 'test@example.com')
            ->set('gstin', 'INVALID_GSTIN')
            ->call('save')
            ->assertHasErrors(['gstin']);
    }

    public function test_valid_gstin_is_accepted(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CustomerForm::class)
            ->set('name', 'ABC Pvt Ltd')
            ->set('phone', '+91 9500000004')
            ->set('email', 'abc@example.com')
            ->set('gstin', '27ABCDE1234F1Z5')
            ->set('company_name', 'ABC Pvt Ltd')
            ->set('country', 'India')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('customers', ['gstin' => '27ABCDE1234F1Z5']);
    }

    public function test_gstin_requires_company_name(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CustomerForm::class)
            ->set('name', 'Test')
            ->set('phone', '+91 9500000005')
            ->set('email', 'gsttest@example.com')
            ->set('gstin', '27ABCDE1234F1Z5')
            // company_name intentionally omitted
            ->call('save')
            ->assertHasErrors(['company_name']);
    }

    // ── UPDATE ────────────────────────────────────────────────────────────────

    public function test_admin_can_update_customer(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);
        $customer = Customer::factory()->create();

        Livewire::test(CustomerForm::class, ['ulid' => $customer->ulid])
            ->set('name', 'Updated Name')
            ->call('save');

        $this->assertDatabaseHas('customers', ['id' => $customer->id, 'name' => 'Updated Name']);
    }

    public function test_agent_can_update_customer(): void
    {
        $this->actingAs($this->agent());
        $customer = Customer::factory()->create();

        Livewire::test(CustomerForm::class, ['ulid' => $customer->ulid])
            ->set('name', 'Agent Edited')
            ->call('save');

        $this->assertDatabaseHas('customers', ['id' => $customer->id, 'name' => 'Agent Edited']);
    }

    // ── SOFT-DELETE ───────────────────────────────────────────────────────────

    public function test_admin_can_soft_delete_customer(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);
        $customer = Customer::factory()->create();

        Livewire::test(CustomersIndex::class)
            ->call('deleteCustomer', $customer->id);

        $this->assertSoftDeleted('customers', ['id' => $customer->id]);
    }

    public function test_soft_deleted_customer_hidden_by_default(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);
        $customer = Customer::factory()->create(['name' => 'Gone Customer']);
        $customer->delete();

        Livewire::test(CustomersIndex::class)
            ->assertDontSee('Gone Customer');
    }

    public function test_soft_deleted_customer_visible_when_show_deleted_enabled(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);
        $customer = Customer::factory()->create(['name' => 'Archived One']);
        $customer->delete();

        Livewire::test(CustomersIndex::class)
            ->set('showDeleted', true)
            ->assertSee('Archived One');
    }

    public function test_admin_can_restore_soft_deleted_customer(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);
        $customer = Customer::factory()->create();
        $customer->delete();

        Livewire::test(CustomersIndex::class)
            ->set('showDeleted', true)
            ->call('restoreCustomer', $customer->id);

        $this->assertNotSoftDeleted('customers', ['id' => $customer->id]);
    }

    // ── RBAC — agent cannot delete ────────────────────────────────────────────

    public function test_agent_cannot_delete_customer(): void
    {
        $this->actingAs($this->agent());
        $customer = Customer::factory()->create();

        Livewire::test(CustomersIndex::class)
            ->call('deleteCustomer', $customer->id)
            ->assertForbidden();
    }

    public function test_agent_cannot_show_deleted_toggle(): void
    {
        $this->actingAs($this->agent());
        $customer = Customer::factory()->create(['name' => 'Hidden One']);
        $customer->delete();

        // Even if agent sets showDeleted=true, the query ignores it.
        Livewire::test(CustomersIndex::class)
            ->set('showDeleted', true)
            ->assertDontSee('Hidden One');
    }
}
