<?php

declare(strict_types=1);

namespace App\Livewire\Admin\SupplierDocs;

use App\Models\SupplierDocument;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class SupplierDocsIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'type')]
    public string $filterType = 'all';

    #[Url(as: 'attached')]
    public string $filterAttached = 'all';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterType(): void
    {
        $this->resetPage();
    }

    public function updatedFilterAttached(): void
    {
        $this->resetPage();
    }

    /**
     * @return LengthAwarePaginator<int, SupplierDocument>
     */
    #[Computed]
    public function documents(): LengthAwarePaginator
    {
        $query = SupplierDocument::query()
            ->with([
                'booking.customer',
                'booking.documents' => fn ($q) => $q->orderByDesc('version_number')->orderByDesc('id'),
                'supplierVendor',
                'uploadedBy',
            ])
            ->orderByDesc('created_at');

        if ($this->search !== '') {
            $needle = '%'.$this->search.'%';
            $query->where(function ($q) use ($needle): void {
                $q->where('original_filename', 'like', $needle)
                    ->orWhere('supplier_name', 'like', $needle)
                    ->orWhereHas('booking', fn ($b) => $b->where('booking_ref', 'like', $needle))
                    ->orWhereHas('booking.customer', fn ($c) => $c->where('name', 'like', $needle));
            });
        }

        if ($this->filterType !== 'all') {
            $query->where('doc_type', $this->filterType);
        }

        if ($this->filterAttached === 'attached') {
            $query->whereNotNull('booking_id');
        } elseif ($this->filterAttached === 'orphan') {
            $query->whereNull('booking_id');
        }

        return $query->paginate(20);
    }

    public function render(): View
    {
        return view('livewire.admin.supplier-docs.supplier-docs-index');
    }
}
