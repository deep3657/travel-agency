<?php

declare(strict_types=1);

namespace App\Livewire\Admin\SupplierDocs;

use App\Jobs\ExtractAction;
use App\Models\Booking;
use App\Models\Trip;
use App\Models\Vendor;
use App\Services\SupplierDocService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Throwable;

/**
 * Standalone "supplier document → booking" wizard (PRD §5.6, M11).
 *
 * Three branches in step 2:
 *  - Create new booking → pick trip, then admin lands in BookingForm prefilled
 *    from the AI extraction (or empty if Manual). Maruti-branded voucher is
 *    auto-generated once the booking is saved.
 *  - Attach to existing booking → upload only; supplier doc is linked to a
 *    booking that was already created some other way.
 *  - Skip / link later → upload only; doc shows up unlinked in the
 *    supplier-docs index until someone attaches it.
 */
class SupplierDocStandalone extends Component
{
    use WithFileUploads;

    #[Url(as: 'booking')]
    public ?string $bookingUlid = null;

    #[Url(as: 'trip')]
    public ?string $tripUlid = null;

    public int $step = 1;

    /** Which step-2 branch the admin chose. */
    #[Validate('required|in:create_booking,attach_existing,skip')]
    public string $nextAction = 'create_booking';

    #[Validate('required|file|mimes:pdf,png,jpg,jpeg|max:10240')]
    public mixed $file = null;

    #[Validate('required|in:flight,hotel,package,other')]
    public string $doc_type = 'flight';

    #[Validate('required|in:manual,ai')]
    public string $extraction_mode = 'manual';

    #[Validate('nullable|string|max:120')]
    public ?string $supplier_name = null;

    #[Validate('nullable|exists:vendors,id')]
    public ?int $supplier_vendor_id = null;

    #[Validate('nullable|exists:bookings,id')]
    public ?int $booking_id = null;

    #[Validate('nullable|exists:trips,id')]
    public ?int $trip_id = null;

    public function mount(): void
    {
        // Deep-linking from a booking detail page:
        //   /admin/supplier-docs/new?booking={ulid}
        if ($this->bookingUlid !== null && $this->bookingUlid !== '') {
            $booking = Booking::query()->where('ulid', $this->bookingUlid)->first();
            if ($booking !== null) {
                $this->booking_id = $booking->id;
                $this->nextAction = 'attach_existing';
            }
        }

        // Deep-linking from a trip detail page:
        //   /admin/supplier-docs/new?trip={ulid}
        if ($this->tripUlid !== null && $this->tripUlid !== '') {
            $trip = Trip::query()->where('ulid', $this->tripUlid)->first();
            if ($trip !== null) {
                $this->trip_id = $trip->id;
                $this->nextAction = 'create_booking';
            }
        }
    }

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
        // If the temporary upload was cleaned up (e.g. session restart, expired
        // tmp file), surface a clear error instead of failing silently when the
        // validator stringifies a missing file into a confusing message.
        if (! $this->file instanceof TemporaryUploadedFile) {
            $this->step = 1;
            $this->addError('file', 'Please re-select the file — the previous upload session expired.');

            return;
        }

        // Branch-specific validation: the picker we ask for depends on what
        // the admin chose to do next.
        $rules = [];
        if ($this->nextAction === 'create_booking') {
            $rules['trip_id'] = 'required|exists:trips,id';
        } elseif ($this->nextAction === 'attach_existing') {
            $rules['booking_id'] = 'required|exists:bookings,id';
        }
        if ($rules !== []) {
            $this->validate($rules);
        }
        $this->validate();

        try {
            $sd = $service->upload($this->file, [
                'doc_type' => $this->doc_type,
                'extraction_mode' => $this->extraction_mode,
                'supplier_name' => $this->supplier_name,
                'supplier_vendor_id' => $this->supplier_vendor_id,
                // Only attach to an existing booking on the "attach_existing"
                // branch. The "create_booking" branch attaches the doc later,
                // after the new booking has actually been saved.
                'booking_id' => $this->nextAction === 'attach_existing' ? $this->booking_id : null,
            ], auth()->user());
        } catch (Throwable $e) {
            Log::error('Supplier document upload failed', [
                'message' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
            $this->addError('file', 'Upload failed: '.$e->getMessage());

            return;
        }

        // Run extraction synchronously when in AI mode so the booking form
        // we redirect to can be prefilled in the same request. dispatchSync
        // bypasses the queue worker entirely — useful in dev environments
        // where no worker is running, and acceptable in prod for the ~3-8s
        // a Gemini call typically takes (PRD §13).
        if ($this->extraction_mode === 'ai') {
            $extractionJob = $service->queueExtraction($sd);
            try {
                ExtractAction::dispatchSync($extractionJob->id);
            } catch (Throwable $e) {
                Log::warning('Synchronous AI extraction failed; admin will fill the booking form by hand.', [
                    'message' => $e->getMessage(),
                    'supplier_document_id' => $sd->id,
                ]);
                // Don't abort — we still want the admin to land on the
                // booking form. They'll just see no prefilled values and a
                // banner explaining that extraction failed.
            }
        }

        match ($this->nextAction) {
            'create_booking' => $this->redirect(
                route('admin.bookings.create', [
                    'from_supplier_doc' => $sd->ulid,
                    'trip_id' => $this->trip_id,
                ]),
                navigate: true,
            ),
            'attach_existing' => $this->redirect(
                route('admin.bookings.show', Booking::query()->find($this->booking_id)?->ulid),
                navigate: true,
            ),
            default => $this->redirect(route('admin.supplier-docs.index'), navigate: true),
        };

        session()->flash('status', match ($this->nextAction) {
            'create_booking' => $this->extraction_mode === 'ai'
                ? 'Document uploaded and fields extracted — review the prefilled booking below.'
                : 'Document uploaded — fill in the booking details below.',
            'attach_existing' => 'Document attached to booking.',
            default => 'Document uploaded successfully.',
        });
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

    /**
     * @return Collection<int, Trip>
     */
    #[Computed]
    public function recentTrips(): Collection
    {
        return Trip::query()->with('customer')->orderBy('created_at', 'desc')->limit(20)->get();
    }

    #[Computed]
    public function selectedBooking(): ?Booking
    {
        if ($this->booking_id === null) {
            return null;
        }

        return Booking::query()->with('customer')->find($this->booking_id);
    }

    #[Computed]
    public function selectedTrip(): ?Trip
    {
        if ($this->trip_id === null) {
            return null;
        }

        return Trip::query()->with('customer')->find($this->trip_id);
    }

    public function render(): View
    {
        return view('livewire.admin.supplier-docs.supplier-doc-standalone');
    }
}
