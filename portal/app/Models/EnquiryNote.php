<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $enquiry_id
 * @property int|null $author_user_id
 * @property string $body
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class EnquiryNote extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'enquiry_id',
        'author_user_id',
        'body',
    ];

    /**
     * @return BelongsTo<Enquiry, $this>
     */
    public function enquiry(): BelongsTo
    {
        return $this->belongsTo(Enquiry::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_user_id');
    }
}
