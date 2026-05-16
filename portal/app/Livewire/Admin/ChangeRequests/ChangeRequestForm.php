<?php

declare(strict_types=1);

namespace App\Livewire\Admin\ChangeRequests;

use App\Models\ChangeRequest;
use App\Services\ChangeRequestService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ChangeRequestForm extends Component
{
    public ChangeRequest $changeRequest;

    #[Validate('required|string|max:20')]
    public string $status = 'open';

    #[Validate('nullable|numeric|min:0')]
    public ?string $vendor_fee = null;

    #[Validate('nullable|numeric|min:0')]
    public ?string $refund_from_vendor = null;

    #[Validate('nullable|numeric|min:0')]
    public ?string $agency_service_fee = null;

    public string $computed_net_refund = '0.00';

    #[Validate('nullable|string|max:20')]
    public ?string $refund_mode = null;

    #[Validate('nullable|string')]
    public ?string $customer_facing_summary = null;

    public function mount(string $ulid): void
    {
        $this->changeRequest = ChangeRequest::with(['booking.customer', 'notes.author'])->where('ulid', $ulid)->firstOrFail();
        abort_unless(auth()->user()?->can('update', $this->changeRequest), 403);

        $this->status = $this->changeRequest->status;
        $this->vendor_fee = $this->changeRequest->vendor_fee?->toDecimalString();
        $this->refund_from_vendor = $this->changeRequest->refund_from_vendor?->toDecimalString();
        $this->agency_service_fee = $this->changeRequest->agency_service_fee?->toDecimalString();
        $this->refund_mode = $this->changeRequest->refund_mode;
        $this->customer_facing_summary = $this->changeRequest->customer_facing_summary;

        $this->recalculate();
    }

    public function updatedVendorFee(): void
    {
        $this->recalculate();
    }

    public function updatedRefundFromVendor(): void
    {
        $this->recalculate();
    }

    public function updatedAgencyServiceFee(): void
    {
        $this->recalculate();
    }

    public function recalculate(): void
    {
        $refund = (float) ($this->refund_from_vendor ?? 0);
        $vendorFee = (float) ($this->vendor_fee ?? 0);
        $agencyFee = (float) ($this->agency_service_fee ?? 0);
        $net = $refund - $vendorFee - $agencyFee;
        $this->computed_net_refund = number_format($net, 2, '.', '');
    }

    public function save(ChangeRequestService $service): void
    {
        $this->validate();

        $data = [
            'status' => $this->status,
            'vendor_fee' => $this->vendor_fee ? (float) $this->vendor_fee : null,
            'refund_from_vendor' => $this->refund_from_vendor ? (float) $this->refund_from_vendor : null,
            'agency_service_fee' => $this->agency_service_fee ? (float) $this->agency_service_fee : null,
            'refund_mode' => $this->refund_mode,
            'customer_facing_summary' => $this->customer_facing_summary,
        ];

        $service->update($this->changeRequest, $data, auth()->user());
        $this->changeRequest->refresh();

        session()->flash('status', 'Change request updated.');
    }

    public function complete(ChangeRequestService $service): void
    {
        abort_unless(auth()->user()?->can('complete', $this->changeRequest), 403);
        $service->complete($this->changeRequest, auth()->user());
        session()->flash('status', 'Change request completed.');
        $this->redirect(route('admin.change-requests.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin.change-requests.change-request-form');
    }
}
