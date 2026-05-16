<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Booking;
use App\Services\ReportQueryService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/** @implements WithMapping<Booking> */
class BookingsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function __construct(private readonly array $filters = []) {}

    /** @return Collection<int, mixed> */
    public function collection(): Collection
    {
        return app(ReportQueryService::class)->bookingsRegister($this->filters);
    }

    /** @return list<string> */
    public function headings(): array
    {
        return [
            'Booking Ref', 'Customer', 'Type', 'Destination',
            'Travel Start', 'Sale Amount', 'Payment Status', 'Status', 'Vendor', 'Created At',
        ];
    }

    /** @return list<mixed> */
    public function map(mixed $row): array
    {
        return [
            $row->booking_ref,
            $row->customer->name ?? '',
            $row->booking_type,
            $row->trip->primary_destination ?? '',
            $row->trip->travel_start?->format('d/m/Y') ?? '',
            $row->sale_amount->toDecimalString(),
            $row->payment_status,
            $row->status,
            $row->vendor->name ?? '',
            $row->created_at->format('d/m/Y'),
        ];
    }
}
