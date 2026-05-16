<?php

declare(strict_types=1);

namespace App\Services\Pdf;

use App\Contracts\PdfRenderer;
use Barryvdh\DomPDF\Facade\Pdf;

final class DompdfRenderer implements PdfRenderer
{
    /**
     * @param  array<string, mixed>  $options
     */
    public function renderHtml(string $html, array $options = []): string
    {
        $pdf = Pdf::loadHTML($html);

        if (isset($options['paper'])) {
            $pdf->setPaper($options['paper'], $options['orientation'] ?? 'portrait');
        }

        return (string) $pdf->output();
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $options
     */
    public function renderView(string $view, array $data = [], array $options = []): string
    {
        $pdf = Pdf::loadView($view, $data);

        if (isset($options['paper'])) {
            $pdf->setPaper($options['paper'], $options['orientation'] ?? 'portrait');
        }

        return (string) $pdf->output();
    }
}
