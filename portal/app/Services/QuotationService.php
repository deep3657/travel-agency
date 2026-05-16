<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Quotation;
use App\Models\QuotationVersion;
use App\Models\Trip;
use App\Models\User;
use App\Support\Money\MoneyVo;
use Illuminate\Support\Str;

final class QuotationService
{
    public function __construct(private readonly GstCalculator $gst) {}

    public function createDraftForTrip(Trip $trip, User $actor): Quotation
    {
        $quotation = Quotation::query()->create([
            'ulid' => (string) Str::ulid(),
            'trip_id' => $trip->id,
            'status' => 'draft',
        ]);

        $version = $this->addVersion($quotation, [], [], $actor);
        $quotation->update(['current_version_id' => $version->id]);

        return $quotation->refresh();
    }

    /**
     * @param  array<int, array<string, mixed>>  $lineData
     * @param  array<string, mixed>  $meta
     */
    public function addVersion(Quotation $q, array $lineData, array $meta, User $actor): QuotationVersion
    {
        $lastVersion = $q->versions()->max('version_number') ?? 0;

        $totals = $this->computeTotals($lineData, $meta);

        $version = QuotationVersion::query()->create([
            'quotation_id' => $q->id,
            'version_number' => $lastVersion + 1,
            'subtotal' => $totals['subtotal'],
            'discount_amount' => $totals['discount_amount'],
            'cgst' => $totals['cgst'],
            'sgst' => $totals['sgst'],
            'igst' => $totals['igst'],
            'grand_total' => $totals['grand_total'],
            'terms' => $meta['terms'] ?? null,
            'customer_notes' => $meta['customer_notes'] ?? null,
        ]);

        foreach ($lineData as $line) {
            $qty = (float) ($line['quantity'] ?? 1);
            $rate = (float) ($line['unit_rate'] ?? 0);
            $amount = $qty * $rate;

            $version->lines()->create([
                'line_type' => $line['line_type'] ?? 'other',
                'description' => $line['description'] ?? '',
                'quantity' => $qty,
                'unit_rate' => $rate,
                'amount' => $amount,
                'vendor_id' => $line['vendor_id'] ?? null,
                'purchase_cost' => isset($line['purchase_cost']) ? (float) $line['purchase_cost'] : null,
                'package_id' => $line['package_id'] ?? null,
            ]);
        }

        $q->update(['current_version_id' => $version->id]);

        return $version->refresh();
    }

    /**
     * @param  array<int, array<string, mixed>>  $lines
     * @param  array<string, mixed>  $meta
     * @return array{subtotal: MoneyVo, discount_amount: MoneyVo, cgst: MoneyVo, sgst: MoneyVo, igst: MoneyVo, grand_total: MoneyVo}
     */
    public function computeTotals(array $lines, array $meta): array
    {
        $subtotal = MoneyVo::zero();
        foreach ($lines as $line) {
            $qty = (float) ($line['quantity'] ?? 1);
            $rate = (float) ($line['unit_rate'] ?? 0);
            $subtotal = $subtotal->plus(MoneyVo::rupees($qty * $rate));
        }

        $discount = MoneyVo::rupees((float) ($meta['discount_amount'] ?? 0));
        $taxable = $subtotal->minus($discount);

        $serviceType = $meta['service_type'] ?? 'other';
        $customerState = $meta['customer_state'] ?? null;

        $gstBreakdown = $this->gst->compute($taxable, $serviceType, $customerState);

        $grandTotal = $taxable
            ->plus($gstBreakdown['cgst'])
            ->plus($gstBreakdown['sgst'])
            ->plus($gstBreakdown['igst']);

        return [
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'cgst' => $gstBreakdown['cgst'],
            'sgst' => $gstBreakdown['sgst'],
            'igst' => $gstBreakdown['igst'],
            'grand_total' => $grandTotal,
        ];
    }

    public function markSent(QuotationVersion $v, User $actor): void
    {
        $v->update(['sent_at' => now()]);
        $v->quotation->update(['status' => 'sent']);
    }

    public function accept(QuotationVersion $v, User $actor): void
    {
        $v->quotation->update(['status' => 'accepted']);
    }
}
