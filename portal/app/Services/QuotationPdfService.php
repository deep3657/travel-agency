<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\PdfRenderer;
use App\Models\Document;
use App\Models\QuotationVersion;
use App\Models\User;
use Illuminate\Support\Str;

final class QuotationPdfService
{
    public function __construct(private readonly PdfRenderer $renderer) {}

    public function generate(QuotationVersion $v, User $actor): Document
    {
        $v->load(['quotation.trip.customer', 'lines.package', 'lines.vendor']);

        $pdfBytes = $this->renderer->renderView('pdf.quotation', [
            'version' => $v,
            'quotation' => $v->quotation,
            'trip' => $v->quotation->trip,
            'customer' => $v->quotation->trip->customer,
        ]);

        $ulid = (string) Str::ulid();
        $path = 'documents/quotations/'.$ulid.'.pdf';

        $directory = storage_path('app/private/documents/quotations');
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents(storage_path('app/private/'.$path), $pdfBytes);

        $v->update(['pdf_path' => $path]);

        return Document::query()->create([
            'ulid' => $ulid,
            'doc_type' => 'quotation',
            'quotation_version_id' => $v->id,
            'version_number' => $v->version_number,
            'pdf_path' => $path,
            'size_bytes' => strlen($pdfBytes),
            'generated_by' => $actor->id,
            'generated_at' => now(),
        ]);
    }
}
