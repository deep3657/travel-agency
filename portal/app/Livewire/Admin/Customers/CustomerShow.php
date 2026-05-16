<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Customers;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * Customer detail page (LLD §5.1).
 * Tabs: Overview | Enquiries (M6) | Trips (M7) | Bookings (M9) | Documents (M11).
 */
class CustomerShow extends Component
{
    public Customer $customer;

    public string $activeTab = 'overview';

    public function mount(string $ulid): void
    {
        $this->customer = Customer::where('ulid', $ulid)->firstOrFail();
        abort_unless(auth()->user()?->can('view', $this->customer), 403);
    }

    public function render(): View
    {
        return view('livewire.admin.customers.customer-show');
    }
}
