<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Concerns\HasUlid;
use App\Support\Concerns\TracksAuthor;
use Carbon\Carbon;
use Database\Factories\PassengerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $ulid
 * @property int|null $customer_id
 * @property string|null $title
 * @property string $first_name
 * @property string $last_name
 * @property Carbon|null $dob
 * @property string|null $nationality
 * @property string|null $passport_number
 * @property Carbon|null $passport_expiry
 * @property string|null $meal_pref
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Passenger extends Model
{
    /** @use HasFactory<PassengerFactory> */
    use HasFactory;

    use HasUlid;
    use TracksAuthor;

    /** @var list<string> */
    protected $fillable = [
        'ulid',
        'customer_id',
        'title',
        'first_name',
        'last_name',
        'dob',
        'nationality',
        'passport_number',
        'passport_expiry',
        'meal_pref',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'passport_expiry' => 'date',
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
     * @return BelongsToMany<Booking, $this>
     */
    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class)->withPivot('is_lead');
    }

    public function getFullNameAttribute(): string
    {
        return trim(($this->title ? $this->title.' ' : '').$this->first_name.' '.$this->last_name);
    }
}
