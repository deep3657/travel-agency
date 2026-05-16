<?php

declare(strict_types=1);

namespace App\Contracts;

interface PdfRenderer
{
    /**
     * Render HTML to PDF and return binary PDF bytes.
     *
     * @param  array<string, mixed>  $options
     */
    public function renderHtml(string $html, array $options = []): string;

    /**
     * Render a Blade view to PDF and return binary PDF bytes.
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $options
     */
    public function renderView(string $view, array $data = [], array $options = []): string;
}
