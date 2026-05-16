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
class SalesProfitExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function __construct(private readonly array $filters = []) {}

    /** @return Collection<int, mixed> */
    public function collection(): Collection
    {
        return app(ReportQueryService::class)->salesProfit($this->filters);
    }

    /** @return list<string> */
    public function headings(): array
    {
        return [
            'Booking Ref', 'Customer', 'Type', 'Vendor',
            'Sale Amount', 'Purchase Cost', 'Margin', 'Margin %', 'Status', 'Created At',
        ];
    }

    /** @return list<mixed> */
    public function map(mixed $row): array
    {
        $sale = $row->sale_amount->toRupees();
        $cost = $row->purchase_cost ? $row->purchase_cost->toRupees() : 0.0;
        $margin = $sale - $cost;
        $marginPct = $sale > 0 ? round(($margin / $sale) * 100, 2) : 0;

        return [
            $row->booking_ref,
            $row->customer->name ?? '',
            $row->booking_type,
            $row->vendor->name ?? '',
            number_format($sale, 2),
            number_format($cost, 2),
            number_format($margin, 2),
            $marginPct.'%',
            $row->status,
            $row->created_at->format('d/m/Y'),
        ];
    }
}
