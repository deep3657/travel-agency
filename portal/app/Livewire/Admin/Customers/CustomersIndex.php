<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Customers;

use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Paginated, searchable customer list (LLD §9, §5.1).
 * Supports search by name, phone, email, or GSTIN.
 * Admin users can toggle showing soft-deleted records.
 */
class CustomersIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public bool $showDeleted = false;

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('viewAny', Customer::class), 403);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedShowDeleted(): void
    {
        $this->resetPage();
    }

    /**
     * @return LengthAwarePaginator<int, Customer>
     */
    #[Computed]
    public function customers(): LengthAwarePaginator
    {
        $query = Customer::query()
            ->when($this->showDeleted && auth()->user()?->isAdmin(), fn ($q) => $q->withTrashed())
            ->when($this->search !== '', function ($q) {
                $term = '%'.$this->search.'%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', $term)
                        ->orWhere('phone', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('gstin', 'like', $term);
                });
            })
            ->orderBy('name');

        return $query->paginate(20);
    }

    public function deleteCustomer(int $id, CustomerService $service): void
    {
        $customer = Customer::findOrFail($id);
        abort_unless(auth()->user()?->can('delete', $customer), 403);

        $service->delete($customer, auth()->user());

        session()->flash('status', 'Customer "'.$customer->name.'" has been archived.');
        $this->resetPage();
    }

    public function restoreCustomer(int $id, CustomerService $service): void
    {
        /** @var Customer $customer */
        $customer = Customer::withTrashed()->findOrFail($id);
        abort_unless(auth()->user()?->can('restore', $customer), 403);

        $service->restore($customer, auth()->user());

        session()->flash('status', 'Customer "'.$customer->name.'" restored.');
    }

    public function render(): View
    {
        return view('livewire.admin.customers.customers-index');
    }
}
