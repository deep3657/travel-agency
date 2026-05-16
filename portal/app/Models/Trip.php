<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Concerns\HasUlid;
use App\Support\Concerns\TracksAuthor;
use Carbon\Carbon;
use Database\Factories\TripFactory;
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
 * @property string $name
 * @property string|null $primary_destination
 * @property Carbon|null $travel_start
 * @property Carbon|null $travel_end
 * @property string $status
 * @property string|null $notes
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Trip extends Model
{
    /** @use HasFactory<TripFactory> */
    use HasFactory;

    use HasUlid;
    use LogsActivity;
    use TracksAuthor;

    /** @var list<string> */
    protected $fillable = [
        'ulid',
        'customer_id',
        'assigned_user_id',
        'name',
        'primary_destination',
        'travel_start',
        'travel_end',
        'status',
        'notes',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'travel_start' => 'date',
            'travel_end' => 'date',
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
     * @return HasMany<Quotation, $this>
     */
    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }

    /**
     * @return HasMany<Booking, $this>
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'status', 'primary_destination', 'travel_start', 'travel_end'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
