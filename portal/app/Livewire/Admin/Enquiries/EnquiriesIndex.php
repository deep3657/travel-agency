<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Enquiries;

use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class EnquiriesIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $filterStatus = 'all';

    #[Url]
    public string $filterAssigned = 'all';

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('viewAny', Enquiry::class), 403);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }

    /**
     * @return LengthAwarePaginator<int, Enquiry>
     */
    #[Computed]
    public function enquiries(): LengthAwarePaginator
    {
        return Enquiry::query()
            ->with(['customer', 'assignedUser'])
            ->when($this->filterStatus !== 'all', fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterAssigned === 'mine', fn ($q) => $q->where('assigned_user_id', auth()->id()))
            ->when($this->filterAssigned === 'unassigned', fn ($q) => $q->whereNull('assigned_user_id'))
            ->when($this->search !== '', function ($q) {
                $term = '%'.$this->search.'%';
                $q->whereHas('customer', fn ($cq) => $cq->where('name', 'like', $term)->orWhere('email', 'like', $term))
                    ->orWhere('destination', 'like', $term)
                    ->orWhere('ulid', 'like', $term);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    /**
     * @return Collection<int, User>
     */
    #[Computed]
    public function agents(): Collection
    {
        return User::query()
            ->where('user_type', User::TYPE_STAFF)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.admin.enquiries.enquiries-index');
    }
}
