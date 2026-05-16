<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\MoneyCast;
use App\Support\Concerns\HasUlid;
use App\Support\Concerns\TracksAuthor;
use App\Support\Money\MoneyVo;
use Carbon\Carbon;
use Database\Factories\PackageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $ulid
 * @property string $slug
 * @property string $title
 * @property string $destinations
 * @property string|null $departure_city
 * @property int $duration_days
 * @property int $duration_nights
 * @property MoneyVo $price_from_inr
 * @property string|null $hero_image_path
 * @property string|null $short_description
 * @property string|null $long_description
 * @property array<int, string>|null $highlights
 * @property array<int, string>|null $inclusions
 * @property array<int, string>|null $exclusions
 * @property string|null $terms
 * @property array<int, string>|null $category_tags
 * @property string|null $seo_meta_title
 * @property string|null $seo_meta_description
 * @property string $status
 * @property Carbon|null $published_at
 * @property Carbon|null $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Package extends Model
{
    /** @use HasFactory<PackageFactory> */
    use HasFactory;

    use HasUlid;
    use LogsActivity;
    use SoftDeletes;
    use TracksAuthor;

    /** @var list<string> */
    protected $fillable = [
        'ulid',
        'slug',
        'title',
        'destinations',
        'departure_city',
        'duration_days',
        'duration_nights',
        'price_from_inr',
        'hero_image_path',
        'short_description',
        'long_description',
        'highlights',
        'inclusions',
        'exclusions',
        'terms',
        'category_tags',
        'seo_meta_title',
        'seo_meta_description',
        'status',
        'published_at',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'highlights' => 'array',
            'inclusions' => 'array',
            'exclusions' => 'array',
            'category_tags' => 'array',
            'price_from_inr' => MoneyCast::class,
            'published_at' => 'datetime',
            'duration_days' => 'integer',
            'duration_nights' => 'integer',
        ];
    }

    /**
     * @return HasMany<PackageImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(PackageImage::class)->orderBy('sort_order');
    }

    /**
     * @return HasMany<ItineraryDay, $this>
     */
    public function itineraryDays(): HasMany
    {
        return $this->hasMany(ItineraryDay::class)->orderBy('day_number');
    }

    /**
     * @return HasMany<Enquiry, $this>
     */
    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'slug', 'status', 'price_from_inr', 'destinations'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
