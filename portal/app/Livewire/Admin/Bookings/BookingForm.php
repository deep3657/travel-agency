<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Bookings;

use App\Models\Booking;
use App\Models\Passenger;
use App\Models\SupplierDocument;
use App\Models\Trip;
use App\Models\Vendor;
use App\Services\BookingService;
use App\Services\VoucherService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Throwable;

class BookingForm extends Component
{
    public ?Booking $booking = null;

    public bool $isEdit = false;

    public string $activeTab = 'details';

    #[Validate('required|exists:trips,id')]
    public ?int $trip_id = null;

    #[Validate('required|in:flight,hotel,package')]
    public string $booking_type = 'package';

    #[Validate('nullable|string|max:40')]
    public ?string $agency_pnr = null;

    #[Validate('nullable|exists:vendors,id')]
    public ?int $vendor_id = null;

    #[Validate('nullable|string|max:40')]
    public ?string $vendor_pnr = null;

    #[Validate('required|numeric|min:0')]
    public string $sale_amount = '0';

    #[Validate('nullable|numeric|min:0')]
    public ?string $purchase_cost = null;

    #[Validate('required|in:unpaid,partial,paid')]
    public string $payment_status = 'unpaid';

    #[Validate('nullable|date')]
    public ?string $customer_payment_due = null;

    #[Validate('nullable|date')]
    public ?string $vendor_payment_due = null;

    #[Validate('required|string|max:20')]
    public string $status = 'pending_confirmation';

    #[Validate('nullable|string')]
    public ?string $notes = null;

    /** @var array<string, mixed> */
    public array $flight_data = [
        'airline' => '', 'flight_no' => '', 'origin' => '', 'destination' => '',
        'departure_datetime' => '', 'arrival_datetime' => '', 'class' => '', 'baggage' => '',
    ];

    /** @var array<string, mixed> */
    public array $hotel_data = [
        'hotel_name' => '', 'check_in' => '', 'check_out' => '', 'room_type' => '',
        'adults' => 1, 'children' => 0,
    ];

    /** @var array<string, mixed> */
    public array $package_data = [
        'package_name' => '', 'travel_start' => '', 'travel_end' => '', 'inclusions_summary' => '',
    ];

    /** @var list<int> */
    public array $passengerIds = [];

    public ?int $leadPassengerId = null;

    /**
     * Draft passengers created inline on this form. Each entry is
     * ['title' => 'Mr', 'first_name' => 'John', 'last_name' => 'Doe'].
     * On save, these are persisted as Passenger rows tied to the trip's
     * customer and then attached to the booking alongside $passengerIds.
     *
     * @var list<array<string, string>>
     */
    public array $newPassengers = [];

    public string $newTitle = 'Mr';

    public string $newFirstName = '';

    public string $newLastName = '';

    /**
     * ULID of the SupplierDocument this booking was created from (if any).
     * Captured from the `?from_supplier_doc={ulid}` query string, threaded
     * through the form, and used on save to (a) link the doc to the new
     * booking and (b) auto-generate the standardised Maruti voucher.
     */
    #[Url(as: 'from_supplier_doc')]
    public ?string $fromSupplierDocUlid = null;

    /** Cached SupplierDocument loaded once in mount(); shown in the banner. */
    public ?int $supplierDocumentId = null;

    public ?string $supplierDocumentFilename = null;

    public ?string $extractionStatus = null;

    public ?string $extractionProvider = null;

    /** @var array<string, float> */
    public array $extractionConfidence = [];

    /** @var list<string> */
    public array $lowConfidenceFields = [];

    /**
     * Confidence threshold below which a field is flagged for human review
     * (PRD §13 — admins review low-confidence fields before saving).
     */
    private const CONFIDENCE_THRESHOLD = 0.7;

    public function mount(?string $ulid = null, ?int $tripId = null): void
    {
        if ($ulid !== null) {
            $this->booking = Booking::with(['passengers'])->where('ulid', $ulid)->firstOrFail();
            $this->isEdit = true;
            abort_unless(auth()->user()?->can('update', $this->booking), 403);

            $this->trip_id = $this->booking->trip_id;
            $this->booking_type = $this->booking->booking_type;
            $this->agency_pnr = $this->booking->agency_pnr;
            $this->vendor_id = $this->booking->vendor_id;
            $this->vendor_pnr = $this->booking->vendor_pnr;
            $this->sale_amount = (string) $this->booking->sale_amount->toRupees();
            $this->purchase_cost = $this->booking->purchase_cost ? (string) $this->booking->purchase_cost->toRupees() : null;
            $this->payment_status = $this->booking->payment_status;
            $this->customer_payment_due = $this->booking->customer_payment_due?->format('Y-m-d');
            $this->vendor_payment_due = $this->booking->vendor_payment_due?->format('Y-m-d');
            $this->status = $this->booking->status;
            $this->notes = $this->booking->notes;
            $this->flight_data = array_merge($this->flight_data, $this->booking->flight_data ?? []);
            $this->hotel_data = array_merge($this->hotel_data, $this->booking->hotel_data ?? []);
            $this->package_data = array_merge($this->package_data, $this->booking->package_data ?? []);
            $this->passengerIds = $this->booking->passengers->pluck('id')->toArray();
        } else {
            abort_unless(auth()->user()?->can('create', Booking::class), 403);
            $this->trip_id = $tripId;
            $this->applySupplierDocPrefill();
        }
    }

    /**
     * If the form was opened from the "supplier doc → new booking" wizard,
     * load the SupplierDocument + its ExtractionJob and prefill the form.
     * Manual-mode uploads (no extraction job) still work — the admin just
     * sees an empty form and a banner reminding them to fill it in.
     */
    private function applySupplierDocPrefill(): void
    {
        if ($this->fromSupplierDocUlid === null || $this->fromSupplierDocUlid === '') {
            return;
        }

        $sd = SupplierDocument::query()
            ->with('extractionJob')
            ->where('ulid', $this->fromSupplierDocUlid)
            ->first();

        if ($sd === null) {
            return;
        }

        $this->supplierDocumentId = $sd->id;
        $this->supplierDocumentFilename = $sd->original_filename;

        // Map supplier doc type → booking type. "other" stays on the default
        // package booking type and the admin can change it.
        $this->booking_type = match ($sd->doc_type) {
            'flight' => 'flight',
            'hotel' => 'hotel',
            'package' => 'package',
            default => $this->booking_type,
        };

        if ($sd->supplier_vendor_id !== null) {
            $this->vendor_id = $sd->supplier_vendor_id;
        }

        $extraction = $sd->extractionJob;
        if ($extraction === null) {
            // Manual mode — nothing to prefill.
            return;
        }

        $this->extractionStatus = $extraction->status;
        $this->extractionProvider = $extraction->provider;

        if ($extraction->status !== 'completed' || empty($extraction->extracted_json)) {
            return;
        }

        /** @var array<string, mixed> $data */
        $data = $extraction->extracted_json;
        /** @var array<string, mixed> $rawConf */
        $rawConf = $extraction->confidence_json ?? [];
        $this->extractionConfidence = array_filter(
            array_map(fn ($v) => is_numeric($v) ? (float) $v : null, $rawConf),
            fn ($v) => $v !== null,
        );

        $this->lowConfidenceFields = $this->prefillFromExtraction($data, $this->booking_type);
        $this->prefillPassengersFromExtraction($data);
    }

    /**
     * Pull passenger names out of the AI-extracted payload into draft entries
     * so the admin sees them pre-filled on the form (still editable).
     *
     * @param  array<string, mixed>  $data
     */
    private function prefillPassengersFromExtraction(array $data): void
    {
        $raw = $data['passengers'] ?? null;
        if (! is_array($raw)) {
            return;
        }

        foreach ($raw as $entry) {
            $name = is_array($entry) ? ($entry['name'] ?? null) : (is_string($entry) ? $entry : null);
            if (! is_string($name) || $name === '') {
                continue;
            }
            $parsed = $this->splitPassengerName($name);
            if ($parsed === null) {
                continue;
            }
            $this->newPassengers[] = $parsed;
        }
    }

    /**
     * Copy AI-extracted values onto the form's typed fields. Returns the list
     * of public field names whose confidence was below the review threshold.
     *
     * @param  array<string, mixed>  $data
     * @return list<string>
     */
    private function prefillFromExtraction(array $data, string $bookingType): array
    {
        $lowConfidence = [];

        $pickString = function (string $key) use ($data): ?string {
            $val = $data[$key] ?? null;

            return is_string($val) && $val !== '' ? $val : null;
        };

        $pickDate = function (string $key, ?string $format = null) use ($data): ?string {
            $val = $data[$key] ?? null;
            if (! is_string($val) || $val === '') {
                return null;
            }
            try {
                $dt = Carbon::parse($val);
            } catch (Throwable) {
                return null;
            }

            return $format !== null ? $dt->format($format) : $dt->toDateString();
        };

        $flagLow = function (string $sourceKey, string $publicField) use (&$lowConfidence): void {
            $confidence = $this->extractionConfidence[$sourceKey] ?? null;
            if ($confidence !== null && $confidence < self::CONFIDENCE_THRESHOLD) {
                $lowConfidence[] = $publicField;
            }
        };

        if ($bookingType === 'flight') {
            $this->flight_data = array_merge($this->flight_data, array_filter([
                'airline' => $pickString('airline'),
                'flight_no' => $pickString('flight_no'),
                'origin' => $pickString('origin'),
                'destination' => $pickString('destination'),
                'class' => $pickString('class'),
                'departure_datetime' => $pickDate('departure_datetime', 'Y-m-d\TH:i'),
                'arrival_datetime' => $pickDate('arrival_datetime', 'Y-m-d\TH:i'),
            ], fn ($v) => $v !== null));

            foreach ([
                'airline' => 'flight_data.airline',
                'flight_no' => 'flight_data.flight_no',
                'origin' => 'flight_data.origin',
                'destination' => 'flight_data.destination',
                'class' => 'flight_data.class',
                'departure_datetime' => 'flight_data.departure_datetime',
                'arrival_datetime' => 'flight_data.arrival_datetime',
            ] as $src => $pub) {
                $flagLow($src, $pub);
            }

            $pnr = $pickString('pnr');
            if ($pnr !== null) {
                $this->vendor_pnr = $pnr;
                $flagLow('pnr', 'vendor_pnr');
            }
        } elseif ($bookingType === 'hotel') {
            $this->hotel_data = array_merge($this->hotel_data, array_filter([
                'hotel_name' => $pickString('hotel_name'),
                'room_type' => $pickString('room_type'),
                'check_in' => $pickDate('check_in'),
                'check_out' => $pickDate('check_out'),
            ], fn ($v) => $v !== null));

            foreach ([
                'hotel_name' => 'hotel_data.hotel_name',
                'room_type' => 'hotel_data.room_type',
                'check_in' => 'hotel_data.check_in',
                'check_out' => 'hotel_data.check_out',
            ] as $src => $pub) {
                $flagLow($src, $pub);
            }

            $checkIn = $this->hotel_data['check_in'] ?? null;
            if (is_string($checkIn) && $checkIn !== '') {
                $this->customer_payment_due = $checkIn;
            }

            $conf = $pickString('confirmation_no');
            if ($conf !== null) {
                $this->vendor_pnr = $conf;
                $flagLow('confirmation_no', 'vendor_pnr');
            }
        }

        return array_values(array_unique($lowConfidence));
    }

    public function save(BookingService $service, VoucherService $voucherService): void
    {
        $this->validate();

        $user = auth()->user();

        $typeData = match ($this->booking_type) {
            'flight' => ['flight_data' => $this->flight_data],
            'hotel' => ['hotel_data' => $this->hotel_data],
            default => ['package_data' => $this->package_data],
        };

        $data = array_merge([
            'trip_id' => $this->trip_id,
            'customer_id' => Trip::find($this->trip_id)?->customer_id,
            'booking_type' => $this->booking_type,
            'agency_pnr' => $this->agency_pnr,
            'vendor_id' => $this->vendor_id,
            'vendor_pnr' => $this->vendor_pnr,
            'sale_amount' => (float) $this->sale_amount,
            'payment_status' => $this->payment_status,
            'status' => $this->status,
            'notes' => $this->notes,
            'customer_payment_due' => $this->customer_payment_due,
        ], $typeData);

        if ($user?->isAdmin()) {
            $data['purchase_cost'] = $this->purchase_cost ? (float) $this->purchase_cost : null;
            $data['vendor_payment_due'] = $this->vendor_payment_due;
        }

        $tripCustomerId = Trip::find($this->trip_id)?->customer_id;
        $createdIds = $this->persistNewPassengers($tripCustomerId);
        $allPassengerIds = array_values(array_unique(array_merge($this->passengerIds, $createdIds)));
        $leadId = $this->leadPassengerId;
        if ($leadId === null && $allPassengerIds !== []) {
            $leadId = $allPassengerIds[0];
        }

        if ($this->isEdit && $this->booking) {
            $booking = $service->update($this->booking, $data, $user);
            $service->attachPassengers($booking, $allPassengerIds, $leadId);
            session()->flash('status', 'Booking updated.');
            $this->redirect(route('admin.bookings.show', $booking->ulid), navigate: true);

            return;
        }

        $booking = $service->create($data, $user);
        $service->attachPassengers($booking, $allPassengerIds, $leadId);

        // If the booking was created from a supplier-doc upload, finish the
        // pipeline: link the doc and generate the Maruti-branded voucher so
        // the admin lands on a ready-to-share booking.
        $voucherGenerated = false;
        if ($this->supplierDocumentId !== null && $user !== null) {
            $sd = SupplierDocument::query()->find($this->supplierDocumentId);
            if ($sd !== null && $sd->booking_id === null) {
                $sd->update(['booking_id' => $booking->id]);
            }

            try {
                $voucherService->generate($booking, $user);
                $voucherGenerated = true;
            } catch (Throwable $e) {
                // Generating the voucher is best-effort — if rendering fails
                // (e.g. missing PHP gd extension in dev), we still save the
                // booking and let the admin click "Generate voucher" by hand.
                Log::warning('Auto-generation of voucher from supplier doc failed.', [
                    'booking_id' => $booking->id,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        session()->flash(
            'status',
            $voucherGenerated
                ? 'Booking created and Maruti voucher generated.'
                : 'Booking created.',
        );
        $this->redirect(route('admin.bookings.show', $booking->ulid), navigate: true);
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
     * @return Collection<int, Trip>
     */
    #[Computed]
    public function trips(): Collection
    {
        return Trip::query()->with('customer')->orderBy('name')->get();
    }

    #[Computed]
    public function showFinancials(): bool
    {
        return (bool) auth()->user()?->isAdmin();
    }

    /**
     * @return Collection<int, Passenger>
     */
    #[Computed]
    public function availablePassengers(): Collection
    {
        if ($this->trip_id === null) {
            return new Collection;
        }

        $trip = Trip::with('customer')->find($this->trip_id);
        if ($trip === null) {
            return new Collection;
        }

        return Passenger::query()
            ->where('customer_id', $trip->customer_id)
            ->orderBy('first_name')
            ->get();
    }

    /**
     * Persist any inline draft passengers as Passenger rows attached to the
     * trip's customer, and return the resulting IDs.
     *
     * @return list<int>
     */
    private function persistNewPassengers(?int $customerId): array
    {
        if ($customerId === null || $this->newPassengers === []) {
            return [];
        }

        $ids = [];
        foreach ($this->newPassengers as $p) {
            $first = trim((string) ($p['first_name'] ?? ''));
            $last = trim((string) ($p['last_name'] ?? ''));
            if ($first === '' && $last === '') {
                continue;
            }
            $passenger = Passenger::query()->create([
                'customer_id' => $customerId,
                'title' => $p['title'] ?? 'Mr',
                'first_name' => $first ?: '—',
                'last_name' => $last,
            ]);
            $ids[] = $passenger->id;
        }
        $this->newPassengers = [];

        return $ids;
    }

    public function addNewPassenger(): void
    {
        $first = trim($this->newFirstName);
        $last = trim($this->newLastName);
        if ($first === '' && $last === '') {
            return;
        }

        $this->newPassengers[] = [
            'title' => $this->newTitle ?: 'Mr',
            'first_name' => $first,
            'last_name' => $last,
        ];

        $this->newTitle = 'Mr';
        $this->newFirstName = '';
        $this->newLastName = '';
    }

    public function removeNewPassenger(int $index): void
    {
        unset($this->newPassengers[$index]);
        $this->newPassengers = array_values($this->newPassengers);
    }

    /**
     * Split an extracted passenger name like "JOHN A. SMITH" or "Mr John Smith"
     * into title/first/last fields.
     *
     * @return array{title: string, first_name: string, last_name: string}|null
     */
    private function splitPassengerName(string $full): ?array
    {
        $full = trim(preg_replace('/\s+/', ' ', $full) ?? '');
        if ($full === '') {
            return null;
        }

        $parts = explode(' ', $full);
        $titles = ['Mr', 'Mrs', 'Ms', 'Miss', 'Dr', 'Master', 'Mstr', 'Mx'];
        $title = 'Mr';
        if (count($parts) > 1) {
            $head = rtrim($parts[0], '.');
            foreach ($titles as $t) {
                if (strcasecmp($head, $t) === 0) {
                    $title = ucfirst(strtolower($t));
                    array_shift($parts);
                    break;
                }
            }
        }

        if ($parts === []) {
            return null;
        }

        $firstName = ucwords(strtolower(array_shift($parts)));
        $lastName = ucwords(strtolower(implode(' ', $parts)));

        return [
            'title' => $title,
            'first_name' => $firstName,
            'last_name' => $lastName,
        ];
    }

    public function render(): View
    {
        return view('livewire.admin.bookings.booking-form');
    }
}
