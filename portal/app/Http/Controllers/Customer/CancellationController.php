<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\ChangeRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CancellationController extends Controller
{
    public function store(Request $request, string $ulid, ChangeRequestService $service): RedirectResponse
    {
        $booking = Booking::where('ulid', $ulid)->firstOrFail();

        $customerId = auth('customer')->user()?->customer?->id;
        abort_unless($booking->customer_id === $customerId, 403);

        $validated = $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        $service->open($booking, [
            'request_type' => 'cancellation',
            'requested_by' => 'customer',
            'requested_by_user_id' => auth('customer')->id(),
            'reason' => $validated['reason'] ?? null,
        ], auth('customer')->user());

        return back()->with('status', 'Cancellation request submitted.');
    }
}
