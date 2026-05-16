<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Document;
use App\Services\VoucherService;
use Illuminate\Http\RedirectResponse;

class VoucherController extends Controller
{
    public function __construct(private readonly VoucherService $service) {}

    public function generate(Booking $booking): RedirectResponse
    {
        abort_unless(auth()->user()?->can('update', $booking), 403);

        $this->service->generate($booking, auth()->user());

        return back()->with('status', 'Voucher generated successfully.');
    }

    public function email(Booking $booking, Document $document): RedirectResponse
    {
        abort_unless(auth()->user()?->can('update', $booking), 403);

        $this->service->emailToCustomer($document, auth()->user());

        return back()->with('status', 'Voucher emailed to customer.');
    }
}
