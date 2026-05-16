<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\MoneyCast;
use App\Support\Concerns\HasUlid;
use App\Support\Concerns\TracksAuthor;
use App\Support\Money\MoneyVo;
use Carbon\Carbon;
use Database\Factories\EnquiryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $ulid
 * @property int $customer_id
 * @property int|null $assigned_user_id
 * @property string $enquiry_type
 * @property Carbon|null $travel_from
 * @property Carbon|null $travel_to
 * @property string|null $origin
 * @property string|null $destination
 * @property int $pax_adult
 * @property int $pax_child
 * @property int $pax_infant
 * @property MoneyVo|null $budget_min
 * @property MoneyVo|null $budget_max
 * @property string|null $special_requirements
 * @property string $status
 * @property string $created_via
 * @property string|null $source
 * @property int|null $package_id
 * @property int|null $converted_to_trip_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Enquiry extends Model
{
    /** @use HasFactory<EnquiryFactory> */
    use HasFactory;

    use HasUlid;
    use LogsActivity;
    use TracksAuthor;

    /** @var list<string> */
    protected $fillable = [
        'ulid',
        'customer_id',
        'assigned_user_id',
        'enquiry_type',
        'travel_from',
        'travel_to',
        'origin',
        'destination',
        'pax_adult',
        'pax_child',
        'pax_infant',
        'budget_min',
        'budget_max',
        'special_requirements',
        'status',
        'created_via',
        'source',
        'package_id',
        'converted_to_trip_id',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'travel_from' => 'date',
            'travel_to' => 'date',
            'budget_min' => MoneyCast::class,
            'budget_max' => MoneyCast::class,
            'pax_adult' => 'integer',
            'pax_child' => 'integer',
            'pax_infant' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    /**
     * @return BelongsTo<Package, $this>
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * @return BelongsTo<Trip, $this>
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class, 'converted_to_trip_id');
    }

    /**
     * @return HasMany<EnquiryNote, $this>
     */
    public function notes(): HasMany
    {
        return $this->hasMany(EnquiryNote::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'assigned_user_id', 'enquiry_type', 'destination'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
