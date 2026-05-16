<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExtractionJob;
use Illuminate\Contracts\View\View;

class ReportsController extends Controller
{
    public function index(): View
    {
        return view('admin.reports.index');
    }

    public function aiExtraction(): View
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $jobs = ExtractionJob::query()
            ->with('supplierDocument')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.reports.ai-extraction', compact('jobs'));
    }
}
