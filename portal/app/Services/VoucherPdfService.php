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

        return Document::query()->create([
            'ulid' => $ulid,
            'doc_type' => $docType,
            'booking_id' => $b->id,
            'pdf_path' => $path,
            'size_bytes' => strlen($pdfBytes),
            'generated_by' => $actor->id,
            'generated_at' => now(),
        ]);
    }
}
