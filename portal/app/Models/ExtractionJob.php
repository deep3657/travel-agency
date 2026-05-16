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
 * @property int $supplier_document_id
 * @property string $status
 * @property string|null $provider
 * @property string|null $model
 * @property Carbon|null $request_started_at
 * @property Carbon|null $request_completed_at
 * @property int|null $response_time_ms
 * @property int|null $prompt_tokens
 * @property int|null $completion_tokens
 * @property string|null $estimated_cost_inr
 * @property array<string, mixed>|null $extracted_json
 * @property array<string, mixed>|null $confidence_json
 * @property string|null $error_code
 * @property string|null $error_message
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ExtractionJob extends Model
{
    use HasUlid;

    /** @var list<string> */
    protected $fillable = [
        'ulid',
        'supplier_document_id',
        'status',
        'provider',
        'model',
        'request_started_at',
        'request_completed_at',
        'response_time_ms',
        'prompt_tokens',
        'completion_tokens',
        'estimated_cost_inr',
        'extracted_json',
        'confidence_json',
        'error_code',
        'error_message',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'request_started_at' => 'datetime',
            'request_completed_at' => 'datetime',
            'extracted_json' => 'array',
            'confidence_json' => 'array',
        ];
    }

    /**
     * @return BelongsTo<SupplierDocument, $this>
     */
    public function supplierDocument(): BelongsTo
    {
        return $this->belongsTo(SupplierDocument::class);
    }
}
