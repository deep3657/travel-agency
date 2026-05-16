<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Trips;

use App\Models\Quotation;
use App\Models\Trip;
use App\Services\QuotationService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class TripShow extends Component
{
    public Trip $trip;

    public string $activeTab = 'overview';

    public function mount(string $ulid): void
    {
        $this->trip = Trip::with([
            'customer', 'assignedUser', 'quotations.currentVersion', 'bookings',
        ])->where('ulid', $ulid)->firstOrFail();

        abort_unless(auth()->user()?->can('view', $this->trip), 403);
    }

    public function createQuotation(): void
    {
        abort_unless(auth()->user()?->can('create', Quotation::class), 403);

        $service = app(QuotationService::class);
        $quotation = $service->createDraftForTrip($this->trip, auth()->user());

        $this->redirect(route('admin.quotations.editor', $quotation->ulid), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin.trips.trip-show');
    }
}
