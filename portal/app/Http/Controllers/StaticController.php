<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Enquiry;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StaticController extends Controller
{
    public function about(): View
    {
        return view('public.about');
    }

    public function contact(): View
    {
        return view('public.contact');
    }

    /**
     * Handle the public "contact us" form. Logged-out visitors submit a name,
     * email, optional phone, and a free-form message. We create a lightweight
     * Customer record (or reuse an existing one keyed by email) and a generic
     * Enquiry so staff can follow up from the admin portal.
     */
    public function contactStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email:rfc|max:190',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:5000',
        ]);

        // Re-use any existing customer with this email to avoid creating
        // duplicates on repeat contacts. Phone is collision-checked below.
        $customer = Customer::query()->where('email', $validated['email'])->first();

        if (! $customer) {
            // If only the phone collides, attach the message to that record
            // instead of failing with a unique-constraint error.
            $customer = Customer::query()->where('phone', $validated['phone'])->first()
                ?? Customer::query()->create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                ]);
        }

        Enquiry::query()->create([
            'ulid' => (string) Str::ulid(),
            'customer_id' => $customer->id,
            'enquiry_type' => 'mixed',
            'special_requirements' => $validated['message'],
            'status' => 'new',
            'created_via' => 'public_contact',
            'source' => 'contact_form',
        ]);

        return redirect()
            ->route('contact')
            ->with('status', "Thanks, {$validated['name']}! We've received your message and will reply within one business day.");
    }
}
