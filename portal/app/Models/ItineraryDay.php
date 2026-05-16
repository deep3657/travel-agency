<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $package_id
 * @property int $day_number
 * @property string $title
 * @property string|null $description
 * @property string|null $image_path
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ItineraryDay extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'package_id',
        'day_number',
        'title',
        'description',
        'image_path',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'day_number' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Package, $this>
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
