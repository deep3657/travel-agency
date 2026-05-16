<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Concerns\HasUlid;
use App\Support\Concerns\TracksAuthor;
use Carbon\Carbon;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Customer master record (LLD §3.2, §4.2).
 *
 * @property int $id
 * @property string $ulid
 * @property string $name
 * @property string $phone
 * @property string|null $alt_phone
 * @property string $email
 * @property string|null $address_line1
 * @property string|null $address_line2
 * @property string|null $city
 * @property string|null $state
 * @property string|null $pincode
 * @property string|null $country
 * @property Carbon|null $dob
 * @property Carbon|null $anniversary
 * @property string|null $gstin
 * @property string|null $company_name
 * @property string|null $pan
 * @property string|null $notes
 * @property array<int, string>|null $tags
 * @property Carbon|null $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use HasFactory;

    use HasUlid;
    use LogsActivity;
    use SoftDeletes;
    use TracksAuthor;

    /** @var list<string> */
    protected $fillable = [
        'ulid',
        'name',
        'phone',
        'alt_phone',
        'email',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'pincode',
        'country',
        'dob',
        'anniversary',
        'gstin',
        'company_name',
        'pan',
        'notes',
        'tags',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'anniversary' => 'date',
            'tags' => 'array',
        ];
    }

    /**
     * Portal user account linked to this customer (M5).
     *
     * @return HasOne<User, $this>
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    /**
     * Enquiries raised by or for this customer (M6).
     *
     * @return HasMany<Enquiry, $this>
     */
    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class);
    }

    /**
     * Trips belonging to this customer (M7).
     *
     * @return HasMany<Trip, $this>
     */
    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    /**
     * Bookings belonging to this customer (M9).
     *
     * @return HasMany<Booking, $this>
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name', 'phone', 'email', 'gstin',
                'address_line1', 'city', 'state', 'pincode',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
