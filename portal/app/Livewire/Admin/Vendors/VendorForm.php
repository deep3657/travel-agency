<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Vendors;

use App\Models\Vendor;
use App\Services\VendorService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

/**
 * Create / edit form for a Vendor record. Admin-only.
 */
class VendorForm extends Component
{
    public ?Vendor $vendor = null;

    public bool $isEdit = false;

    #[Validate('required|string|max:190')]
    public string $name = '';

    #[Validate('nullable|string|max:20')]
    public ?string $code = null;

    #[Validate('nullable|string|max:120')]
    public ?string $contact_person = null;

    #[Validate('nullable|email:rfc|max:190')]
    public ?string $email = null;

    #[Validate('nullable|string|max:20')]
    public ?string $phone = null;

    #[Validate('nullable|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/')]
    public ?string $gstin = null;

    #[Validate('nullable|string|max:1000')]
    public ?string $address = null;

    #[Validate('nullable|integer|min:0|max:365')]
    public int $payment_terms_days = 0;

    #[Validate('nullable|string|max:5000')]
    public ?string $notes = null;

    public function mount(?string $ulid = null): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        if ($ulid !== null) {
            $this->vendor = Vendor::where('ulid', $ulid)->firstOrFail();
            $this->isEdit = true;
            $this->fill($this->vendor->only([
                'name', 'code', 'contact_person', 'email', 'phone',
                'gstin', 'address', 'payment_terms_days', 'notes',
            ]));
        }
    }

    public function save(VendorService $service): void
    {
        $data = $this->validate();
        $data = array_filter($data, fn ($v) => $v !== null && $v !== '');

        if ($this->isEdit && $this->vendor) {
            $this->validateUnique(update: true);
            $service->update($this->vendor, $data, auth()->user());
            session()->flash('status', 'Vendor updated.');
            $this->redirect(route('admin.vendors.show', $this->vendor->ulid), navigate: true);
        } else {
            $this->validateUnique(update: false);
            $vendor = $service->create($data, auth()->user());
            session()->flash('status', 'Vendor created.');
            $this->redirect(route('admin.vendors.show', $vendor->ulid), navigate: true);
        }
    }

    private function validateUnique(bool $update): void
    {
        if ($this->code === null || $this->code === '') {
            return;
        }

        $codeRule = 'unique:vendors,code'.($update && $this->vendor ? ",{$this->vendor->id}" : '');
        $this->validateOnly('code', ['code' => ['nullable', 'string', 'max:20', $codeRule]]);
    }

    public function render(): View
    {
        return view('livewire.admin.vendors.vendor-form');
    }
}
