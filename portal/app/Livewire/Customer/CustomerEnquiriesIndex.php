<?php

declare(strict_types=1);

namespace App\Livewire\Customer;

use App\Models\Enquiry;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('customer.layouts.app')]
class CustomerEnquiriesIndex extends Component
{
    use WithPagination;

    /**
     * @return LengthAwarePaginator<int, Enquiry>
     */
    #[Computed]
    public function enquiries(): LengthAwarePaginator
    {
        $customerId = auth('customer')->user()?->customer?->id;

        return Enquiry::query()
            ->where('customer_id', $customerId)
            ->with(['package'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function render(): View
    {
        return view('livewire.customer.customer-enquiries-index');
    }
}
