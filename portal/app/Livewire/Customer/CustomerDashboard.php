<?php

declare(strict_types=1);

namespace App\Livewire\Customer;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('customer.layouts.app')]
class CustomerDashboard extends Component
{
    public function render(): View
    {
        $user = auth('customer')->user();
        $customer = $user?->customer;

        $customerName = 'Traveller';
        if ($customer instanceof Customer && $customer->name) {
            $customerName = $customer->name;
        } elseif ($user) {
            $customerName = $user->name;
        }

        $enquiryCount = $customer?->enquiries()->count() ?? 0;
        $tripCount = $customer?->trips()->count() ?? 0;
        $upcomingTrip = null;
        $latestEnquiry = null;

        if ($customer) {
            $upcomingTrip = $customer->trips()
                ->whereNotNull('travel_start')
                ->where('travel_start', '>=', now()->toDateString())
                ->orderBy('travel_start')
                ->first();

            $latestEnquiry = $customer->enquiries()
                ->orderByDesc('created_at')
                ->first();
        }

        return view('livewire.customer.customer-dashboard', compact(
            'customer',
            'customerName',
            'enquiryCount',
            'tripCount',
            'upcomingTrip',
            'latestEnquiry',
        ));
    }
}
