<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        try {
            $featuredPackages = Package::query()
                ->where('status', 'active')
                ->orderBy('published_at', 'desc')
                ->limit(6)
                ->get();
        } catch (QueryException) {
            $featuredPackages = collect();
        }

        return view('public.home', compact('featuredPackages'));
    }
}
