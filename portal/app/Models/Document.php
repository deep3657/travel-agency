<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Concerns\HasUlid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $ulid
 * @property string $doc_type
 * @property int|null $booking_id
 * @property int|null $quotation_version_id
 * @property int $version_number
 * @property string $pdf_path
 * @property int $size_bytes
 * @property int|null $generated_by
 * @property Carbon $generated_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Document extends Model
{
    use HasUlid;

    /** @var list<string> */
    protected $fillable = [
        'ulid',
        'doc_type',
        'booking_id',
        'quotation_version_id',
        'version_number',
        'pdf_path',
        'size_bytes',
        'generated_by',
        'generated_at',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'generated_at' => 'datetime',
            'size_bytes' => 'integer',
            'version_number' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Booking, $this>
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * @return BelongsTo<QuotationVersion, $this>
     */
    public function quotationVersion(): BelongsTo
    {
        return $this->belongsTo(QuotationVersion::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
