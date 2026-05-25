<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Trips;

use App\Models\Customer;
use App\Models\Trip;
use App\Models\User;
use App\Services\TripService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

/**
 * Admin-side form for creating a Trip directly, without first going
 * through an Enquiry. The Enquiry → Convert flow remains the primary
 * path, but agents can use this when a customer is already known.
 */
class TripForm extends Component
{
    #[Validate('required|integer|exists:customers,id')]
    public ?int $customer_id = null;

    #[Validate('required|string|max:190')]
    public string $name = '';

    #[Validate('nullable|string|max:120')]
    public ?string $primary_destination = null;

    #[Validate('nullable|date')]
    public ?string $travel_start = null;

    #[Validate('nullable|date|after_or_equal:travel_start')]
    public ?string $travel_end = null;

    #[Validate('nullable|integer|exists:users,id')]
    public ?int $assigned_user_id = null;

    #[Validate('required|in:planning,confirmed,completed,cancelled')]
    public string $status = 'planning';

    #[Validate('nullable|string|max:5000')]
    public ?string $notes = null;

    public string $customerSearch = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('create', Trip::class), 403);
        $this->assigned_user_id = auth()->id();
    }

    /**
     * When a customer is picked, auto-suggest a sensible trip name so the
     * agent rarely has to type one from scratch.
     */
    public function updatedCustomerId(): void
    {
        $this->suggestName();
    }

    public function updatedPrimaryDestination(): void
    {
        $this->suggestName();
    }

    public function updatedTravelStart(): void
    {
        $this->suggestName();
    }

    private function suggestName(): void
    {
        // Don't overwrite something the user has typed themselves.
        if ($this->name !== '' && ! $this->isAutoName($this->name)) {
            return;
        }

        if (! $this->customer_id) {
            return;
        }

        $customer = Customer::find($this->customer_id);
        if (! $customer) {
            return;
        }

        $parts = [$customer->name];
        if ($this->primary_destination) {
            $parts[] = $this->primary_destination;
        }
        if ($this->travel_start) {
            try {
                $parts[] = \Illuminate\Support\Carbon::parse($this->travel_start)->format('M Y');
            } catch (\Throwable) {
                // Ignore unparseable dates – the date validator will catch them on save.
            }
        }

        $this->name = mb_substr(implode(' · ', $parts), 0, 190);
    }

    /**
     * A name is considered auto-generated if it matches the pattern we produce.
     * This keeps "auto-suggest" useful without ever clobbering user input.
     */
    private function isAutoName(string $candidate): bool
    {
        if (! $this->customer_id) {
            return false;
        }
        $customer = Customer::find($this->customer_id);
        if (! $customer) {
            return false;
        }

        return str_starts_with($candidate, $customer->name.' · ')
            || $candidate === $customer->name;
    }

    public function save(TripService $service): void
    {
        $data = $this->validate();
        abort_unless(auth()->user()?->can('create', Trip::class), 403);

        $payload = array_filter([
            'customer_id' => $data['customer_id'],
            'name' => $data['name'],
            'primary_destination' => $data['primary_destination'] ?: null,
            'travel_start' => $data['travel_start'] ?: null,
            'travel_end' => $data['travel_end'] ?: null,
            'assigned_user_id' => $data['assigned_user_id'] ?: null,
            'status' => $data['status'],
            'notes' => $data['notes'] ?: null,
        ], fn ($v) => $v !== null);

        $trip = $service->create($payload, auth()->user());

        session()->flash('status', "Trip created: {$trip->name}");
        $this->redirect(route('admin.trips.show', $trip->ulid), navigate: true);
    }

    /**
     * @return Collection<int, Customer>
     */
    #[Computed]
    public function customerOptions(): Collection
    {
        return Customer::query()
            ->when($this->customerSearch !== '', function ($q) {
                $term = '%'.$this->customerSearch.'%';
                $q->where(function ($qq) use ($term) {
                    $qq->where('name', 'like', $term)
                        ->orWhere('phone', 'like', $term)
                        ->orWhere('email', 'like', $term);
                });
            })
            ->orderBy('name')
            ->limit(50)
            ->get(['id', 'name', 'phone', 'email']);
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
            ->get(['id', 'name']);
    }

    public function render(): View
    {
        return view('livewire.admin.trips.trip-form');
    }
}
