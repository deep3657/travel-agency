<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SignedFileController extends Controller
{
    public function download(Request $request, string $token): BinaryFileResponse|StreamedResponse
    {
        $document = Document::where('ulid', $token)->firstOrFail();

        $user = auth()->user() ?? auth('customer')->user();
        abort_unless($user !== null, 401);

        $filePath = storage_path('app/private/'.$document->pdf_path);
        abort_unless(file_exists($filePath), 404);

        return response()->download($filePath);
    }
}
