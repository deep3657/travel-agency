<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\Enquiry;
use Illuminate\Support\Collection;

final class ReportQueryService
{
    /**
     * @param  array<string, mixed>  $filters
     * @return Collection<int, Booking>
     */
    public function bookingsRegister(array $filters): Collection
    {
        return Booking::query()
            ->with(['customer', 'vendor', 'trip'])
            ->when(isset($filters['date_from']), fn ($q) => $q->where('created_at', '>=', $filters['date_from']))
            ->when(isset($filters['date_to']), fn ($q) => $q->where('created_at', '<=', $filters['date_to']))
            ->when(isset($filters['status']), fn ($q) => $q->where('status', $filters['status']))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return Collection<int, Booking>
     */
    public function salesProfit(array $filters): Collection
    {
        return Booking::query()
            ->with(['customer', 'vendor'])
            ->when(isset($filters['date_from']), fn ($q) => $q->where('created_at', '>=', $filters['date_from']))
            ->when(isset($filters['date_to']), fn ($q) => $q->where('created_at', '<=', $filters['date_to']))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return Collection<int, Enquiry>
     */
    public function enquiryConversion(array $filters): Collection
    {
        return Enquiry::query()
            ->with(['customer'])
            ->when(isset($filters['date_from']), fn ($q) => $q->where('created_at', '>=', $filters['date_from']))
            ->when(isset($filters['date_to']), fn ($q) => $q->where('created_at', '<=', $filters['date_to']))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return Collection<int, Booking>
     */
    public function cancellations(array $filters): Collection
    {
        return Booking::query()
            ->with(['customer', 'changeRequests'])
            ->where('status', 'cancelled')
            ->when(isset($filters['date_from']), fn ($q) => $q->where('created_at', '>=', $filters['date_from']))
            ->when(isset($filters['date_to']), fn ($q) => $q->where('created_at', '<=', $filters['date_to']))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return Collection<int, Booking>
     */
    public function paymentsCustomer(array $filters): Collection
    {
        return Booking::query()
            ->with(['customer'])
            ->when(isset($filters['date_from']), fn ($q) => $q->where('customer_payment_due', '>=', $filters['date_from']))
            ->when(isset($filters['date_to']), fn ($q) => $q->where('customer_payment_due', '<=', $filters['date_to']))
            ->orderBy('customer_payment_due')
            ->get();
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return Collection<int, Booking>
     */
    public function paymentsVendor(array $filters): Collection
    {
        return Booking::query()
            ->with(['vendor'])
            ->when(isset($filters['date_from']), fn ($q) => $q->where('vendor_payment_due', '>=', $filters['date_from']))
            ->when(isset($filters['date_to']), fn ($q) => $q->where('vendor_payment_due', '<=', $filters['date_to']))
            ->orderBy('vendor_payment_due')
            ->get();
    }
}
