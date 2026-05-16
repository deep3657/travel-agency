<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\Trip;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();
        $isAdmin = $user?->isAdmin();

        $totalCustomers = Customer::query()->count();
        $totalBookings = Booking::query()->count();
        $totalEnquiries = Enquiry::query()->count();
        $pendingEnquiries = Enquiry::query()->where('status', 'new')->count();

        $upcomingTrips = Trip::query()
            ->with(['customer'])
            ->whereNotNull('travel_start')
            ->where('travel_start', '>=', now()->toDateString())
            ->where('travel_start', '<=', now()->addDays(7)->toDateString())
            ->orderBy('travel_start')
            ->get();

        $recentEnquiries = Enquiry::query()
            ->with(['customer'])
            ->when(! $isAdmin, fn ($q) => $q->where('assigned_user_id', $user?->id))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $monthlyRevenue = null;
        if ($isAdmin) {
            $monthlyRevenue = Booking::query()
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->sum('sale_amount');
        }

        return view('dashboard', compact(
            'totalCustomers', 'totalBookings', 'totalEnquiries',
            'pendingEnquiries', 'upcomingTrips', 'recentEnquiries', 'monthlyRevenue', 'isAdmin',
        ));
    }
}
