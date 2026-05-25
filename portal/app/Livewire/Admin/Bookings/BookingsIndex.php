<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Bookings;

use App\Models\Booking;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class BookingsIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $filterStatus = 'all';

    #[Url]
    public string $filterType = 'all';

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('viewAny', Booking::class), 403);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function showFinancials(): bool
    {
        return (bool) auth()->user()?->isAdmin();
    }

    /**
     * @return LengthAwarePaginator<int, Booking>
     */
    #[Computed]
    public function bookings(): LengthAwarePaginator
    {
        return Booking::query()
            ->with(['customer', 'vendor', 'trip'])
            ->when($this->filterStatus !== 'all', fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterType !== 'all', fn ($q) => $q->where('booking_type', $this->filterType))
            ->when($this->search !== '', function ($q) {
                $term = '%'.$this->search.'%';
                $q->where('booking_ref', 'like', $term)
                    ->orWhereHas('customer', fn ($cq) => $cq->where('name', 'like', $term));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function render(): View
    {
        return view('livewire.admin.bookings.bookings-index');
    }
}
