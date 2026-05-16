<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Packages;

use App\Models\Package;
use App\Services\PackageService;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PackagesIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public bool $showDeleted = false;

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('viewAny', Package::class), 403);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedShowDeleted(): void
    {
        $this->resetPage();
    }

    /**
     * @return LengthAwarePaginator<int, Package>
     */
    #[Computed]
    public function packages(): LengthAwarePaginator
    {
        return Package::query()
            ->when($this->showDeleted && auth()->user()?->isAdmin(), fn ($q) => $q->withTrashed())
            ->when($this->search !== '', function ($q) {
                $term = '%'.$this->search.'%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('title', 'like', $term)
                        ->orWhere('destinations', 'like', $term)
                        ->orWhere('slug', 'like', $term);
                });
            })
            ->orderBy('title')
            ->paginate(20);
    }

    public function deletePackage(int $id, PackageService $service): void
    {
        $package = Package::findOrFail($id);
        abort_unless(auth()->user()?->can('delete', $package), 403);
        $service->delete($package, auth()->user());
        session()->flash('status', 'Package "'.$package->title.'" archived.');
        $this->resetPage();
    }

    public function restorePackage(int $id, PackageService $service): void
    {
        /** @var Package $package */
        $package = Package::withTrashed()->findOrFail($id);
        abort_unless(auth()->user()?->can('restore', $package), 403);
        $service->restore($package, auth()->user());
        session()->flash('status', 'Package "'.$package->title.'" restored.');
    }

    public function render(): View
    {
        return view('livewire.admin.packages.packages-index');
    }
}
