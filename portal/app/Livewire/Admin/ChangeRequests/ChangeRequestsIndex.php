<?php

declare(strict_types=1);

namespace App\Livewire\Admin\ChangeRequests;

use App\Models\ChangeRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ChangeRequestsIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $filterStatus = 'all';

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('viewAny', ChangeRequest::class), 403);
    }

    /**
     * @return LengthAwarePaginator<int, ChangeRequest>
     */
    #[Computed]
    public function changeRequests(): LengthAwarePaginator
    {
        return ChangeRequest::query()
            ->with(['booking.customer', 'assignedUser'])
            ->when($this->filterStatus !== 'all', fn ($q) => $q->where('status', $this->filterStatus))
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function render(): View
    {
        return view('livewire.admin.change-requests.change-requests-index');
    }
}
