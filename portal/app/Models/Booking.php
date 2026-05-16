<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\MoneyCast;
use App\Services\BookingRefGenerator;
use App\Support\Concerns\HasUlid;
use App\Support\Concerns\TracksAuthor;
use App\Support\Money\MoneyVo;
use Carbon\Carbon;
use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $ulid
 * @property string $booking_ref
 * @property int $trip_id
 * @property int $customer_id
 * @property string $booking_type
 * @property string|null $agency_pnr
 * @property int|null $vendor_id
 * @property string|null $vendor_pnr
 * @property MoneyVo $sale_amount
 * @property MoneyVo|null $purchase_cost
 * @property string $payment_status
 * @property Carbon|null $customer_payment_due
 * @property Carbon|null $vendor_payment_due
 * @property string $status
 * @property array<string, mixed>|null $flight_data
 * @property array<string, mixed>|null $hotel_data
 * @property array<string, mixed>|null $package_data
 * @property string|null $notes
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Booking extends Model
{
    /** @use HasFactory<BookingFactory> */
    use HasFactory;

    use HasUlid;
    use LogsActivity;
    use TracksAuthor;

    /** @var list<string> */
    protected $fillable = [
        'ulid',
        'booking_ref',
        'trip_id',
        'customer_id',
        'booking_type',
        'agency_pnr',
        'vendor_id',
        'vendor_pnr',
        'sale_amount',
        'purchase_cost',
        'payment_status',
        'customer_payment_due',
        'vendor_payment_due',
        'status',
        'flight_data',
        'hotel_data',
        'package_data',
        'notes',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'sale_amount' => MoneyCast::class,
            'purchase_cost' => MoneyCast::class,
            'customer_payment_due' => 'date',
            'vendor_payment_due' => 'date',
            'flight_data' => 'array',
            'hotel_data' => 'array',
            'package_data' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $booking): void {
            if (empty($booking->getAttribute('booking_ref'))) {
                $booking->setAttribute('booking_ref', app(BookingRefGenerator::class)->next());
            }
        });
    }

    /**
     * @return BelongsTo<Trip, $this>
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * @return BelongsTo<Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return BelongsTo<Vendor, $this>
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * @return BelongsToMany<Passenger, $this>
     */
    public function passengers(): BelongsToMany
    {
        return $this->belongsToMany(Passenger::class)->withPivot('is_lead');
    }

    /**
     * @return HasMany<Document, $this>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * @return HasMany<ChangeRequest, $this>
     */
    public function changeRequests(): HasMany
    {
        return $this->hasMany(ChangeRequest::class);
    }

    /**
     * @return HasMany<SupplierDocument, $this>
     */
    public function supplierDocuments(): HasMany
    {
        return $this->hasMany(SupplierDocument::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'payment_status', 'booking_type', 'sale_amount'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
