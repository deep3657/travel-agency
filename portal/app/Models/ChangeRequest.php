<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\MoneyCast;
use App\Support\Concerns\HasUlid;
use App\Support\Concerns\TracksAuthor;
use App\Support\Money\MoneyVo;
use Carbon\Carbon;
use Database\Factories\ChangeRequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $ulid
 * @property int $booking_id
 * @property string $request_type
 * @property string $requested_by
 * @property int|null $requested_by_user_id
 * @property string|null $reason
 * @property string $status
 * @property int|null $assigned_user_id
 * @property MoneyVo|null $vendor_fee
 * @property MoneyVo|null $refund_from_vendor
 * @property MoneyVo|null $agency_service_fee
 * @property MoneyVo|null $net_refund_to_customer
 * @property string|null $refund_mode
 * @property Carbon|null $refund_settled_at
 * @property string|null $customer_facing_summary
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ChangeRequest extends Model
{
    /** @use HasFactory<ChangeRequestFactory> */
    use HasFactory;

    use HasUlid;
    use LogsActivity;
    use TracksAuthor;

    /** @var list<string> */
    protected $fillable = [
        'ulid',
        'booking_id',
        'request_type',
        'requested_by',
        'requested_by_user_id',
        'reason',
        'status',
        'assigned_user_id',
        'vendor_fee',
        'refund_from_vendor',
        'agency_service_fee',
        'net_refund_to_customer',
        'refund_mode',
        'refund_settled_at',
        'customer_facing_summary',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'vendor_fee' => MoneyCast::class,
            'refund_from_vendor' => MoneyCast::class,
            'agency_service_fee' => MoneyCast::class,
            'net_refund_to_customer' => MoneyCast::class,
            'refund_settled_at' => 'datetime',
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
     * @return BelongsTo<User, $this>
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    /**
     * @return HasMany<ChangeRequestNote, $this>
     */
    public function notes(): HasMany
    {
        return $this->hasMany(ChangeRequestNote::class);
    }

    /**
     * Computes net refund from raw financial fields (PHP-side to avoid SQLite generated column issues).
     */
    public function computeNetRefund(): MoneyVo
    {
        $refund = $this->refund_from_vendor ?? MoneyVo::zero();
        $vendorFee = $this->vendor_fee ?? MoneyVo::zero();
        $agencyFee = $this->agency_service_fee ?? MoneyVo::zero();

        return $refund->minus($vendorFee)->minus($agencyFee);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'request_type', 'net_refund_to_customer'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
