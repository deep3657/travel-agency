<?php

declare(strict_types=1);

namespace App\Livewire\Admin\SupplierDocs;

use App\Models\Booking;
use App\Models\Vendor;
use App\Services\SupplierDocService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class SupplierDocStandalone extends Component
{
    use WithFileUploads;

    public int $step = 1;

    #[Validate('required|file|mimes:pdf,png,jpg,jpeg|max:10240')]
    public mixed $file = null;

    #[Validate('required|in:flight,hotel,other')]
    public string $doc_type = 'flight';

    #[Validate('required|in:manual,ai')]
    public string $extraction_mode = 'manual';

    #[Validate('nullable|string|max:120')]
    public ?string $supplier_name = null;

    #[Validate('nullable|exists:vendors,id')]
    public ?int $supplier_vendor_id = null;

    #[Validate('nullable|exists:bookings,id')]
    public ?int $booking_id = null;

    public function nextStep(): void
    {
        if ($this->step === 1) {
            $this->validateOnly('file');
            $this->validateOnly('doc_type');
            $this->validateOnly('extraction_mode');
            $this->step = 2;
        }
    }

    public function save(SupplierDocService $service): void
    {
        $this->validate();

        $sd = $service->upload($this->file, [
            'doc_type' => $this->doc_type,
            'extraction_mode' => $this->extraction_mode,
            'supplier_name' => $this->supplier_name,
            'supplier_vendor_id' => $this->supplier_vendor_id,
            'booking_id' => $this->booking_id,
        ], auth()->user());

        if ($this->extraction_mode === 'ai') {
            $service->queueExtraction($sd);
            session()->flash('status', 'Document uploaded and queued for AI extraction.');
        } else {
            session()->flash('status', 'Document uploaded successfully.');
        }

        $this->redirect(route('admin.supplier-docs.index'), navigate: true);
    }

    /**
     * @return Collection<int, Vendor>
     */
    #[Computed]
    public function vendors(): Collection
    {
        return Vendor::query()->orderBy('name')->get();
    }

    /**
     * @return Collection<int, Booking>
     */
    #[Computed]
    public function recentBookings(): Collection
    {
        return Booking::query()->with('customer')->orderBy('created_at', 'desc')->limit(20)->get();
    }

    public function render(): View
    {
        return view('livewire.admin.supplier-docs.supplier-doc-standalone');
    }
}
