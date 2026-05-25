<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\PdfRenderer;
use App\Models\Booking;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Str;

final class VoucherPdfService
{
    public function __construct(private readonly PdfRenderer $renderer) {}

    public function generate(Booking $b, User $actor): Document
    {
        $b->load(['trip.customer', 'passengers', 'vendor']);

        $view = match ($b->booking_type) {
            'flight' => 'pdf.flight_voucher',
            'hotel' => 'pdf.hotel_voucher',
            default => 'pdf.package_voucher',
        };

        $docType = match ($b->booking_type) {
            'flight' => 'flight_voucher',
            'hotel' => 'hotel_voucher',
            default => 'package_voucher',
        };

        $pdfBytes = $this->renderer->renderView($view, [
            'booking' => $b,
            'customer' => $b->trip->customer,
            'passengers' => $b->passengers,
        ]);

        $ulid = (string) Str::ulid();
        $path = 'documents/vouchers/'.$ulid.'.pdf';

        $directory = storage_path('app/private/documents/vouchers');
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents(storage_path('app/private/'.$path), $pdfBytes);

        // "Drop and create" versioning (PRD §5.5): every regeneration increments
        // the version_number for this (booking, doc_type) pair. Previous
        // documents are kept and remain downloadable from the admin UI.
        $nextVersion = (int) Document::query()
            ->where('booking_id', $b->id)
            ->where('doc_type', $docType)
            ->max('version_number') + 1;

        return Document::query()->create([
            'ulid' => $ulid,
            'doc_type' => $docType,
            'booking_id' => $b->id,
            'version_number' => $nextVersion,
            'pdf_path' => $path,
            'size_bytes' => strlen($pdfBytes),
            'generated_by' => $actor->id,
            'generated_at' => now(),
        ]);
    }
}
