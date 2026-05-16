<?php

declare(strict_types=1);

namespace App\Services;

use App\Jobs\ExtractAction;
use App\Models\Booking;
use App\Models\ExtractionJob;
use App\Models\SupplierDocument;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

final class SupplierDocService
{
    /** @param array<string, mixed> $data */
    public function upload(UploadedFile $file, array $data, User $actor): SupplierDocument
    {
        $ulid = (string) Str::ulid();
        $extension = $file->getClientOriginalExtension();
        $path = 'supplier-docs/'.$ulid.'.'.$extension;

        $directory = storage_path('app/private/supplier-docs');
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $fileContents = (string) file_get_contents($file->getRealPath());
        file_put_contents(storage_path('app/private/'.$path), $fileContents);

        return SupplierDocument::query()->create([
            'ulid' => $ulid,
            'doc_type' => $data['doc_type'] ?? 'other',
            'supplier_name' => $data['supplier_name'] ?? null,
            'supplier_vendor_id' => $data['supplier_vendor_id'] ?? null,
            'original_filename' => $file->getClientOriginalName(),
            'storage_path' => $path,
            'mime' => $file->getMimeType() ?? 'application/octet-stream',
            'size_bytes' => $file->getSize(),
            'sha256' => hash('sha256', $fileContents),
            'extraction_mode' => $data['extraction_mode'] ?? 'manual',
            'uploaded_by' => $actor->id,
            'booking_id' => $data['booking_id'] ?? null,
        ]);
    }

    public function queueExtraction(SupplierDocument $sd): ExtractionJob
    {
        $job = ExtractionJob::query()->create([
            'ulid' => (string) Str::ulid(),
            'supplier_document_id' => $sd->id,
            'status' => 'pending',
        ]);

        ExtractAction::dispatch($job->id);

        return $job;
    }

    public function attachToBooking(SupplierDocument $sd, Booking $b): void
    {
        $sd->update(['booking_id' => $b->id]);
    }
}
