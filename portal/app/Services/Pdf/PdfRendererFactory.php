<?php

declare(strict_types=1);

namespace App\Services\Pdf;

use App\Contracts\PdfRenderer;

final class PdfRendererFactory
{
    public function __construct(private readonly PdfRenderer $renderer) {}

    public function make(): PdfRenderer
    {
        return $this->renderer;
    }
}
