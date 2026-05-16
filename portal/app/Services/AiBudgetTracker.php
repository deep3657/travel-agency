<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AgencySetting;
use App\Models\ExtractionJob;
use App\Models\User;
use App\Support\Money\MoneyVo;

final class AiBudgetTracker
{
    public function monthSpendInr(): MoneyVo
    {
        $total = ExtractionJob::query()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->whereNotNull('estimated_cost_inr')
            ->sum('estimated_cost_inr');

        return MoneyVo::rupees((float) $total);
    }

    public function capInr(): MoneyVo
    {
        $cap = AgencySetting::singleton()->ai_monthly_cap_inr;

        return MoneyVo::rupees((float) $cap);
    }

    public function isCapBreached(): bool
    {
        return $this->monthSpendInr()->paise >= $this->capInr()->paise;
    }

    public function canExtract(User $u): bool
    {
        return ! $this->isCapBreached();
    }

    public function recordCost(ExtractionJob $j): void
    {
        // Cost is already stored on the ExtractionJob row; this method
        // exists so callers can signal cost recording without coupling to the model.
    }
}
