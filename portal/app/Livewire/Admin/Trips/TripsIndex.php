<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Trips;

use App\Models\Trip;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TripsIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $filterStatus = 'all';

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('viewAny', Trip::class), 403);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * @return LengthAwarePaginator<int, Trip>
     */
    #[Computed]
    public function trips(): LengthAwarePaginator
    {
        return Trip::query()
            ->with(['customer', 'assignedUser'])
            ->when($this->filterStatus !== 'all', fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->search !== '', function ($q) {
                $term = '%'.$this->search.'%';
                $q->where('name', 'like', $term)
                    ->orWhere('primary_destination', 'like', $term)
                    ->orWhereHas('customer', fn ($cq) => $cq->where('name', 'like', $term));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function render(): View
    {
        return view('livewire.admin.trips.trips-index');
    }
}
