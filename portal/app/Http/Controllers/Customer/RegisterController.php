<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('customer.auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email:rfc|max:190|unique:users,email',
            'phone' => 'required|string|max:20|unique:customers,phone',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $customer = Customer::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'user_type' => User::TYPE_CUSTOMER,
            'customer_id' => $customer->id,
            'is_active' => true,
        ]);

        event(new Registered($user));

        Auth::guard('customer')->login($user);

        return redirect()->route('customer.account');
    }
}
