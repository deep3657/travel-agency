<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $subject
 * @property string $body_markdown
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class EnquiryReplyTemplate extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'name',
        'subject',
        'body_markdown',
        'is_active',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
