<?php

declare(strict_types=1);

namespace App\Livewire\Customer;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class CustomerDashboard extends Component
{
    public function render(): View
    {
        $user = auth('customer')->user();
        $customer = $user?->customer;

        $enquiryCount = $customer?->enquiries()->count() ?? 0;
        $tripCount = $customer?->trips()->count() ?? 0;

        return view('livewire.customer.customer-dashboard', compact('customer', 'enquiryCount', 'tripCount'));
    }
}
