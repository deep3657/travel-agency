<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $change_request_id
 * @property int|null $author_user_id
 * @property string $body
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ChangeRequestNote extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'change_request_id',
        'author_user_id',
        'body',
    ];

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
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_user_id');
    }
}
