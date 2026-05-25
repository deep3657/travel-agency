<?php

declare(strict_types=1);

namespace App\Livewire\Customer;

use App\Models\Trip;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('customer.layouts.app')]
class CustomerTripsIndex extends Component
{
    use WithPagination;

    /**
     * @return LengthAwarePaginator<int, Trip>
     */
    #[Computed]
    public function trips(): LengthAwarePaginator
    {
        $customerId = auth('customer')->user()?->customer?->id;

        return Trip::query()
            ->where('customer_id', $customerId)
            ->orderBy('travel_start', 'desc')
            ->paginate(10);
    }

    public function render(): View
    {
        return view('livewire.customer.customer-trips-index');
    }
}
