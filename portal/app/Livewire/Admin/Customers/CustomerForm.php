<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Customers;

use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

/**
 * Create / edit form for a Customer record (LLD §5.1).
 * Mounted with an optional Customer ulid; absence means "create".
 */
class CustomerForm extends Component
{
    public ?Customer $customer = null;

    public bool $isEdit = false;

    // ── fields ───────────────────────────────────────────────────────────────

    #[Validate('required|string|max:120')]
    public string $name = '';

    #[Validate('required|string|max:20')]
    public string $phone = '';

    #[Validate('nullable|string|max:20')]
    public ?string $alt_phone = null;

    #[Validate('required|email:rfc|max:190')]
    public string $email = '';

    #[Validate('nullable|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/')]
    public ?string $gstin = null;

    #[Validate('required_with:gstin|nullable|string|max:190')]
    public ?string $company_name = null;

    #[Validate('nullable|regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/')]
    public ?string $pan = null;

    #[Validate('nullable|string|max:190')]
    public ?string $address_line1 = null;

    #[Validate('nullable|string|max:190')]
    public ?string $address_line2 = null;

    #[Validate('nullable|string|max:80')]
    public ?string $city = null;

    #[Validate('nullable|string|max:80')]
    public ?string $state = null;

    #[Validate('nullable|regex:/^[0-9]{6}$/')]
    public ?string $pincode = null;

    #[Validate('nullable|string|max:80')]
    public string $country = 'India';

    #[Validate('nullable|date|before:today')]
    public ?string $dob = null;

    #[Validate('nullable|date')]
    public ?string $anniversary = null;

    #[Validate('nullable|string|max:5000')]
    public ?string $notes = null;

    public function mount(?string $ulid = null): void
    {
        if ($ulid !== null) {
            $this->customer = Customer::where('ulid', $ulid)->firstOrFail();
            $this->isEdit = true;
            abort_unless(auth()->user()?->can('update', $this->customer), 403);
            $this->fill($this->customer->only([
                'name', 'phone', 'alt_phone', 'email', 'gstin', 'company_name',
                'pan', 'address_line1', 'address_line2', 'city', 'state', 'pincode',
                'country', 'notes',
            ]));
            // Dates are cast to Carbon on the model; convert to Y-m-d for the HTML date input.
            $dob = $this->customer->dob;
            $anniversary = $this->customer->anniversary;
            $this->dob = $dob?->format('Y-m-d');
            $this->anniversary = $anniversary?->format('Y-m-d');
        } else {
            abort_unless(auth()->user()?->can('create', Customer::class), 403);
        }
    }

    public function save(CustomerService $service): void
    {
        $data = $this->validate();

        // Remove nulls so unique rules in the request layer don't choke.
        $data = array_filter($data, fn ($v) => $v !== null && $v !== '');

        if ($this->isEdit && $this->customer) {
            // Re-check uniqueness ignoring current row.
            $this->validateUnique(update: true);
            $service->update($this->customer, $data, auth()->user());
            session()->flash('status', 'Customer updated.');
            $this->redirect(route('admin.customers.show', $this->customer->ulid), navigate: true);
        } else {
            $this->validateUnique(update: false);
            $customer = $service->create($data, auth()->user());
            session()->flash('status', 'Customer created.');
            $this->redirect(route('admin.customers.show', $customer->ulid), navigate: true);
        }
    }

    /**
     * Run phone + email uniqueness checks (not expressible via #[Validate]
     * because the ignore ID changes between create and edit).
     */
    private function validateUnique(bool $update): void
    {
        $phoneRule = 'unique:customers,phone'.($update && $this->customer ? ",{$this->customer->id}" : '');
        $emailRule = 'unique:customers,email'.($update && $this->customer ? ",{$this->customer->id}" : '');

        $this->validateOnly('phone', ['phone' => ['required', 'string', 'regex:/^[0-9+\\- ]{8,20}$/', $phoneRule]]);
        $this->validateOnly('email', ['email' => ['required', 'email:rfc', 'max:190', $emailRule]]);
    }

    public function render(): View
    {
        return view('livewire.admin.customers.customer-form');
    }
}
