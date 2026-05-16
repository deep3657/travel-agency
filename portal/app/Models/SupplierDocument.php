<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Concerns\HasUlid;
use App\Support\Concerns\TracksAuthor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $ulid
 * @property int|null $booking_id
 * @property string $doc_type
 * @property string|null $supplier_name
 * @property int|null $supplier_vendor_id
 * @property string $original_filename
 * @property string $storage_path
 * @property string $mime
 * @property int $size_bytes
 * @property string $sha256
 * @property string $extraction_mode
 * @property int|null $uploaded_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class SupplierDocument extends Model
{
    use HasUlid;
    use TracksAuthor;

    /** @var list<string> */
    protected $fillable = [
        'ulid',
        'booking_id',
        'doc_type',
        'supplier_name',
        'supplier_vendor_id',
        'original_filename',
        'storage_path',
        'mime',
        'size_bytes',
        'sha256',
        'extraction_mode',
        'uploaded_by',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'size_bytes' => 'integer',
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
     * @return BelongsTo<Vendor, $this>
     */
    public function supplierVendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'supplier_vendor_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * @return HasOne<ExtractionJob, $this>
     */
    public function extractionJob(): HasOne
    {
        return $this->hasOne(ExtractionJob::class);
    }
}
