<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $package_id
 * @property string $image_path
 * @property int $sort_order
 * @property string|null $alt_text
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class PackageImage extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'package_id',
        'image_path',
        'sort_order',
        'alt_text',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
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
