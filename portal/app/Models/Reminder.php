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
 * @property int|null $booking_id
 * @property int|null $change_request_id
 * @property string $reminder_type
 * @property Carbon $trigger_at
 * @property Carbon|null $fired_at
 * @property string $dedup_key
 * @property int|null $recipient_user_id
 * @property array<string, mixed>|null $payload
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Booking|null $booking
 * @property ChangeRequest|null $changeRequest
 * @property User|null $recipient
 */
class Reminder extends Model
{
    use HasUlid;

    /** @var list<string> */
    protected $fillable = [
        'ulid',
        'booking_id',
        'change_request_id',
        'reminder_type',
        'trigger_at',
        'fired_at',
        'dedup_key',
        'recipient_user_id',
        'payload',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'trigger_at' => 'datetime',
            'fired_at' => 'datetime',
            'payload' => 'array',
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
     * @return BelongsTo<ChangeRequest, $this>
     */
    public function changeRequest(): BelongsTo
    {
        return $this->belongsTo(ChangeRequest::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_user_id');
    }
}
