<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Concerns\HasUlid;
use App\Support\Concerns\TracksAuthor;
use Carbon\Carbon;
use Database\Factories\VendorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Vendor master record (LLD §3.2).
 *
 * @property int $id
 * @property string $ulid
 * @property string $name
 * @property string|null $code
 * @property string|null $contact_person
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $gstin
 * @property string|null $address
 * @property int $payment_terms_days
 * @property string|null $notes
 * @property Carbon|null $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Vendor extends Model
{
    /** @use HasFactory<VendorFactory> */
    use HasFactory;

    use HasUlid;
    use LogsActivity;
    use SoftDeletes;
    use TracksAuthor;

    /** @var list<string> */
    protected $fillable = [
        'ulid',
        'name',
        'code',
        'contact_person',
        'email',
        'phone',
        'gstin',
        'address',
        'payment_terms_days',
        'notes',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'payment_terms_days' => 'integer',
        ];
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
            ->logOnly(['name', 'code', 'email', 'phone', 'gstin', 'payment_terms_days'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
