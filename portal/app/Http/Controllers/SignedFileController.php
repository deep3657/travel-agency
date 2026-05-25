<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\SupplierDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SignedFileController extends Controller
{
    /**
     * Stream a stored file to the user.
     *
     * The `$token` is the ULID of either a generated `Document` (Maruti-branded
     * voucher / quotation / invoice) or a `SupplierDocument` (the original
     * supplier-issued PDF). Access is enforced by:
     *
     *  - the signed-URL middleware on the route (URLs are time-limited and
     *    tamper-evident),
     *  - the `auth` middleware (must be a logged-in staff user or customer),
     *  - and the per-document-type checks below (customers can only download
     *    documents tied to their own bookings; supplier-issued originals are
     *    never exposed to customers).
     */
    public function download(Request $request, string $token): BinaryFileResponse
    {
        $user = auth()->user() ?? auth('customer')->user();
        abort_unless($user instanceof User, 401);

        $document = Document::query()->where('ulid', $token)->first();
        if ($document !== null) {
            return $this->downloadBrandedDocument($document, $user);
        }

        $supplierDocument = SupplierDocument::query()->where('ulid', $token)->first();
        if ($supplierDocument !== null) {
            return $this->downloadSupplierDocument($supplierDocument, $user);
        }

        abort(404);
    }

    private function downloadBrandedDocument(Document $document, User $user): BinaryFileResponse
    {
        // Customers may only download documents that belong to their own
        // bookings; staff may download any document.
        if ($user->isCustomer()) {
            $booking = $document->booking;
            abort_unless(
                $booking !== null && $booking->customer_id === $user->customer_id,
                403,
            );
        }

        $absolutePath = $this->resolveStoragePath($document->pdf_path);
        abort_unless($absolutePath !== null, 404);

        $downloadName = $this->humanFilename($document);

        return response()->download($absolutePath, $downloadName);
    }

    private function downloadSupplierDocument(SupplierDocument $supplierDocument, User $user): BinaryFileResponse
    {
        // Supplier-issued originals are an internal/audit artefact. Per PRD
        // §5.6 they are never exposed to the customer-facing site — only
        // admins and agents may download them.
        abort_unless($user->isAdmin() || $user->isAgent(), 403);

        $absolutePath = $this->resolveStoragePath($supplierDocument->storage_path);
        abort_unless($absolutePath !== null, 404);

        return response()->download(
            $absolutePath,
            $supplierDocument->original_filename,
        );
    }

    /**
     * Resolve a stored relative path to an absolute path on disk, ensuring
     * the result stays inside the configured private storage root. Returns
     * null if the file is missing or the path attempts to escape the root
     * (defence-in-depth against path traversal).
     */
    private function resolveStoragePath(string $relativePath): ?string
    {
        $root = realpath(storage_path('app/private'));
        if ($root === false) {
            return null;
        }

        $candidate = realpath($root.DIRECTORY_SEPARATOR.$relativePath);
        if ($candidate === false) {
            return null;
        }

        if (! str_starts_with($candidate, $root.DIRECTORY_SEPARATOR)) {
            return null;
        }

        if (! is_file($candidate)) {
            return null;
        }

        return $candidate;
    }

    private function humanFilename(Document $document): string
    {
        $label = str_replace('_', '-', $document->doc_type);
        $version = 'v'.$document->version_number;

        $bookingRef = $document->booking?->booking_ref;
        $prefix = $bookingRef !== null ? $bookingRef.'-' : '';

        return $prefix.$label.'-'.$version.'.pdf';
    }
}
