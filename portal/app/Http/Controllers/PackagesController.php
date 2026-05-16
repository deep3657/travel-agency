<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PackagesController extends Controller
{
    public function index(Request $request): View
    {
        $packages = Package::query()
            ->where('status', 'active')
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->whereJsonContains('category_tags', $request->input('category'));
            })
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%'.$request->input('q').'%';
                $q->where('title', 'like', $term)
                    ->orWhere('destinations', 'like', $term)
                    ->orWhere('short_description', 'like', $term);
            })
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('public.packages.index', compact('packages'));
    }

    public function show(string $slug): View
    {
        $package = Package::query()
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        return view('public.packages.show', compact('package'));
    }
}
