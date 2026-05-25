<?php

declare(strict_types=1);

namespace App\Livewire\Customer;

use App\Models\Trip;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('customer.layouts.app')]
class CustomerTripDetail extends Component
{
    public Trip $trip;

    public function mount(string $ulid): void
    {
        $this->trip = Trip::with(['bookings.documents', 'quotations.currentVersion'])
            ->where('ulid', $ulid)
            ->firstOrFail();

        $customerId = auth('customer')->user()?->customer?->id;
        abort_unless($this->trip->customer_id === $customerId, 403);
    }

    public function render(): View
    {
        return view('livewire.customer.customer-trip-detail');
    }
}
