<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AgencySetting;
use App\Support\Money\MoneyVo;

/**
 * Computes GST split (CGST+SGST vs IGST) based on whether the customer
 * is in the same state as the agency.
 */
final class GstCalculator
{
    /**
     * @return array{cgst: MoneyVo, sgst: MoneyVo, igst: MoneyVo, total: MoneyVo}
     */
    public function compute(MoneyVo $taxable, string $serviceType, ?string $customerState): array
    {
        $settings = AgencySetting::singleton();
        $agencyState = $settings->state;

        $rate = match ($serviceType) {
            'package' => (float) $settings->gst_rate_package,
            'flight' => (float) $settings->gst_rate_flight_service,
            'hotel' => (float) $settings->gst_rate_hotel_service,
            default => (float) $settings->gst_rate_other,
        };

        $totalTax = $taxable->times($rate / 100);

        $sameState = $agencyState !== null
            && $customerState !== null
            && strtolower(trim($agencyState)) === strtolower(trim($customerState));

        if ($sameState) {
            $cgst = $taxable->times($rate / 2 / 100);
            $sgst = $taxable->times($rate / 2 / 100);
            $igst = MoneyVo::zero();
        } else {
            $cgst = MoneyVo::zero();
            $sgst = MoneyVo::zero();
            $igst = $totalTax;
        }

        return [
            'cgst' => $cgst,
            'sgst' => $sgst,
            'igst' => $igst,
            'total' => $totalTax,
        ];
    }
}
