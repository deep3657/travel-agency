<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Notifications\EnquiryReceivedNotification;
use App\Services\EnquiryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function store(Request $request, EnquiryService $service): RedirectResponse
    {
        $user = auth('customer')->user();
        $customer = $user?->customer;

        abort_unless($customer !== null, 403);

        $validated = $request->validate([
            'enquiry_type' => 'required|in:flight,hotel,package,mixed',
            'destination' => 'required|string|max:120',
            'travel_from' => 'nullable|date',
            'travel_to' => 'nullable|date|after:travel_from',
            'pax_adult' => 'required|integer|min:1',
            'pax_child' => 'nullable|integer|min:0',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'special_requirements' => 'nullable|string|max:5000',
        ]);

        $validated['customer_id'] = $customer->id;
        $validated['created_via'] = 'customer_portal';
        $validated['pax_child'] ??= 0;

        $enquiry = $service->create($validated, $user);

        $user->notify(new EnquiryReceivedNotification($enquiry));

        return redirect()->route('customer.enquiries')->with('status', 'Enquiry submitted successfully!');
    }
}
