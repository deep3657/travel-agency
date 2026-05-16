<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\MoneyCast;
use App\Support\Concerns\TracksAuthor;
use App\Support\Money\MoneyVo;
use Carbon\Carbon;
use Database\Factories\QuotationLineFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $quotation_version_id
 * @property string $line_type
 * @property string $description
 * @property array<string, mixed>|null $structured_data
 * @property int|null $package_id
 * @property string $quantity
 * @property MoneyVo $unit_rate
 * @property MoneyVo $amount
 * @property int|null $vendor_id
 * @property MoneyVo|null $purchase_cost
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class QuotationLine extends Model
{
    /** @use HasFactory<QuotationLineFactory> */
    use HasFactory;

    use TracksAuthor;

    /** @var list<string> */
    protected $fillable = [
        'quotation_version_id',
        'line_type',
        'description',
        'structured_data',
        'package_id',
        'quantity',
        'unit_rate',
        'amount',
        'vendor_id',
        'purchase_cost',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'structured_data' => 'array',
            'unit_rate' => MoneyCast::class,
            'amount' => MoneyCast::class,
            'purchase_cost' => MoneyCast::class,
            'quantity' => 'decimal:2',
        ];
    }

    /**
     * @return BelongsTo<QuotationVersion, $this>
     */
    public function version(): BelongsTo
    {
        return $this->belongsTo(QuotationVersion::class, 'quotation_version_id');
    }

    /**
     * @return BelongsTo<Vendor, $this>
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * @return BelongsTo<Package, $this>
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
