<?php

declare(strict_types=1);

namespace App\Livewire\Customer;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('customer.layouts.app')]
class CustomerProfile extends Component
{
    public ?Customer $customer = null;

    #[Validate('required|string|max:120')]
    public string $name = '';

    #[Validate('required|string|max:20')]
    public string $phone = '';

    #[Validate('nullable|string|max:190')]
    public ?string $address_line1 = null;

    #[Validate('nullable|string|max:80')]
    public ?string $city = null;

    #[Validate('nullable|string|max:80')]
    public ?string $state = null;

    #[Validate('nullable|string|max:80')]
    public string $country = 'India';

    public function mount(): void
    {
        $user = auth('customer')->user();
        $this->customer = $user?->customer;

        if ($this->customer) {
            $this->name = $this->customer->name;
            $this->phone = $this->customer->phone;
            $this->address_line1 = $this->customer->address_line1;
            $this->city = $this->customer->city;
            $this->state = $this->customer->state;
            $this->country = $this->customer->country ?? 'India';
        }
    }

    public function save(): void
    {
        $this->validate();

        if ($this->customer) {
            $this->customer->update([
                'name' => $this->name,
                'phone' => $this->phone,
                'address_line1' => $this->address_line1,
                'city' => $this->city,
                'state' => $this->state,
                'country' => $this->country,
            ]);

            session()->flash('status', 'Profile updated successfully.');
        }
    }

    public function render(): View
    {
        return view('livewire.customer.customer-profile');
    }
}
