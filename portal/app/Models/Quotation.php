<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Concerns\HasUlid;
use App\Support\Concerns\TracksAuthor;
use Carbon\Carbon;
use Database\Factories\QuotationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $ulid
 * @property int $trip_id
 * @property int|null $current_version_id
 * @property Carbon|null $validity_date
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Quotation extends Model
{
    /** @use HasFactory<QuotationFactory> */
    use HasFactory;

    use HasUlid;
    use LogsActivity;
    use TracksAuthor;

    /** @var list<string> */
    protected $fillable = [
        'ulid',
        'trip_id',
        'current_version_id',
        'validity_date',
        'status',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'validity_date' => 'date',
        ];
    }

    /**
     * @return BelongsTo<Trip, $this>
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * @return HasMany<QuotationVersion, $this>
     */
    public function versions(): HasMany
    {
        return $this->hasMany(QuotationVersion::class)->orderBy('version_number');
    }

    /**
     * @return BelongsTo<QuotationVersion, $this>
     */
    public function currentVersion(): BelongsTo
    {
        return $this->belongsTo(QuotationVersion::class, 'current_version_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'current_version_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
