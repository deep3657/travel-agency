<div>
    <div class="mt-card">
        <div class="mt-card-header flex-wrap gap-3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search trips..." class="mt-input w-64">
            <select wire:model.live="filterStatus" class="mt-select w-auto">
                <option value="all">All statuses</option>
                <option value="planning">Planning</option>
                <option value="confirmed">Confirmed</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <div class="overflow-x-auto">
            <table class="mt-table">
                <thead>
                    <tr>
                        <th>Trip</th>
                        <th>Customer</th>
                        <th>Destination</th>
                        <th>Travel dates</th>
                        <th>Status</th>
                        <th>Agent</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->trips as $trip)
                        <tr>
                            <td class="font-medium text-ink-900">{{ $trip->name }}</td>
                            <td>{{ $trip->customer->name }}</td>
                            <td>{{ $trip->primary_destination ?? '—' }}</td>
                            <td class="text-ink-500">{{ $trip->travel_start?->format('d M') ?? '—' }} – {{ $trip->travel_end?->format('d M Y') ?? '' }}</td>
                            <td><x-status-pill :status="$trip->status" /></td>
                            <td>{{ $trip->assignedUser?->name ?? '—' }}</td>
                            <td class="text-right"><a href="{{ route('admin.trips.show', $trip->ulid) }}" class="text-brand-700 hover:text-brand-800 hover:underline text-sm">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-ink-400 py-8">No trips found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-ink-200/70">{{ $this->trips->links() }}</div>
    </div>
</div>
