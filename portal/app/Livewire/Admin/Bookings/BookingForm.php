<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Bookings;

use App\Models\Booking;
use App\Models\Passenger;
use App\Models\Trip;
use App\Models\Vendor;
use App\Services\BookingService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

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
        }
    }

    public function save(BookingService $service): void
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

        if ($this->isEdit && $this->booking) {
            $booking = $service->update($this->booking, $data, $user);
            $service->attachPassengers($booking, $this->passengerIds, $this->leadPassengerId);
            session()->flash('status', 'Booking updated.');
            $this->redirect(route('admin.bookings.show', $booking->ulid), navigate: true);
        } else {
            $booking = $service->create($data, $user);
            $service->attachPassengers($booking, $this->passengerIds, $this->leadPassengerId);
            session()->flash('status', 'Booking created.');
            $this->redirect(route('admin.bookings.show', $booking->ulid), navigate: true);
        }
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

    public function render(): View
    {
        return view('livewire.admin.bookings.booking-form');
    }
}
