<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Exports\BookingsExport;
use App\Exports\SalesProfitExport;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportsExportController extends Controller
{
    public function bookings(Request $request): BinaryFileResponse
    {
        abort_unless(auth()->user()?->can('viewAny', Booking::class), 403);

        return Excel::download(
            new BookingsExport($request->only(['date_from', 'date_to', 'status'])),
            'bookings-'.now()->format('Y-m-d').'.xlsx',
        );
    }

    public function salesProfit(Request $request): BinaryFileResponse
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        return Excel::download(
            new SalesProfitExport($request->only(['date_from', 'date_to'])),
            'sales-profit-'.now()->format('Y-m-d').'.xlsx',
        );
    }
}
