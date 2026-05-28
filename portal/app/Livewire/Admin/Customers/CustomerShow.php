<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Customers;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
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

    public function markEmailVerified(): void
    {
        abort_unless(auth()->user()?->can('update', $this->customer), 403);

        $user = $this->customer->user;
        if ($user === null) {
            session()->flash('verify_error', 'No portal account exists for this customer yet.');

            return;
        }

        if ($user->hasVerifiedEmail()) {
            session()->flash('verify_status', 'Email is already verified.');

            return;
        }

        $user->forceFill(['email_verified_at' => Carbon::now()])->save();
        $this->customer->refresh();

        session()->flash('verify_status', 'Customer email marked as verified.');
    }

    public function render(): View
    {
        return view('livewire.admin.customers.customer-show');
    }
}
