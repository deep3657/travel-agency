<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\MoneyCast;
use App\Support\Concerns\TracksAuthor;
use App\Support\Money\MoneyVo;
use Carbon\Carbon;
use Database\Factories\QuotationVersionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $quotation_id
 * @property int $version_number
 * @property MoneyVo $subtotal
 * @property MoneyVo $discount_amount
 * @property MoneyVo $cgst
 * @property MoneyVo $sgst
 * @property MoneyVo $igst
 * @property MoneyVo $grand_total
 * @property string|null $terms
 * @property string|null $customer_notes
 * @property string|null $pdf_path
 * @property Carbon|null $sent_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class QuotationVersion extends Model
{
    /** @use HasFactory<QuotationVersionFactory> */
    use HasFactory;

    use LogsActivity;
    use TracksAuthor;

    /** @var list<string> */
    protected $fillable = [
        'quotation_id',
        'version_number',
        'subtotal',
        'discount_amount',
        'cgst',
        'sgst',
        'igst',
        'grand_total',
        'terms',
        'customer_notes',
        'pdf_path',
        'sent_at',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'subtotal' => MoneyCast::class,
            'discount_amount' => MoneyCast::class,
            'cgst' => MoneyCast::class,
            'sgst' => MoneyCast::class,
            'igst' => MoneyCast::class,
            'grand_total' => MoneyCast::class,
            'sent_at' => 'datetime',
            'version_number' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Quotation, $this>
     */
    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    /**
     * @return HasMany<QuotationLine, $this>
     */
    public function lines(): HasMany
    {
        return $this->hasMany(QuotationLine::class);
    }

    /**
     * @return HasMany<Document, $this>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['version_number', 'grand_total', 'sent_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
