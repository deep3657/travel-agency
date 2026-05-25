<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\ExtractionJob;
use Illuminate\Contracts\View\View;

class ReportsController extends Controller
{
    public function index(): View
    {
        $isAdmin = (bool) auth()->user()?->isAdmin();

        $bookings = Booking::query()
            ->with(['customer', 'vendor'])
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        $pendingEnquiries = Enquiry::query()
            ->whereIn('status', ['new', 'in_progress'])
            ->count();

        $confirmedCustomers = Customer::query()->count();

        return view('admin.reports.index', compact(
            'bookings', 'pendingEnquiries', 'confirmedCustomers', 'isAdmin',
        ));
    }

    public function aiExtraction(): View
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $jobs = ExtractionJob::query()
            ->with('supplierDocument')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.reports.ai-extraction', compact('jobs'));
    }
}
