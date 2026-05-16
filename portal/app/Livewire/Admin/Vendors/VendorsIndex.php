<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Vendors;

use App\Models\Vendor;
use App\Services\VendorService;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Admin-only paginated vendor list with search and soft-delete controls.
 */
class VendorsIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public bool $showDeleted = false;

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('viewAny', Vendor::class), 403);
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
     * @return LengthAwarePaginator<int, Vendor>
     */
    #[Computed]
    public function vendors(): LengthAwarePaginator
    {
        return Vendor::query()
            ->when($this->showDeleted, fn ($q) => $q->withTrashed())
            ->when($this->search !== '', function ($q) {
                $term = '%'.$this->search.'%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', $term)
                        ->orWhere('code', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('phone', 'like', $term)
                        ->orWhere('gstin', 'like', $term);
                });
            })
            ->orderBy('name')
            ->paginate(20);
    }

    public function deleteVendor(int $id, VendorService $service): void
    {
        $vendor = Vendor::findOrFail($id);
        abort_unless(auth()->user()?->can('delete', $vendor), 403);

        $service->delete($vendor, auth()->user());

        session()->flash('status', 'Vendor "'.$vendor->name.'" archived.');
        $this->resetPage();
    }

    public function restoreVendor(int $id, VendorService $service): void
    {
        /** @var Vendor $vendor */
        $vendor = Vendor::withTrashed()->findOrFail($id);
        abort_unless(auth()->user()?->can('restore', $vendor), 403);

        $service->restore($vendor, auth()->user());

        session()->flash('status', 'Vendor "'.$vendor->name.'" restored.');
    }

    public function render(): View
    {
        return view('livewire.admin.vendors.vendors-index');
    }
}
