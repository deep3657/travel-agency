<div>
    <div class="mt-card">
        <div class="mt-card-header flex-wrap gap-3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search enquiries..." class="mt-input w-64">
            <select wire:model.live="filterStatus" class="mt-select w-auto">
                <option value="all">All statuses</option>
                <option value="new">New</option>
                <option value="in_progress">In progress</option>
                <option value="quoted">Quoted</option>
                <option value="closed">Closed</option>
            </select>
            <select wire:model.live="filterAssigned" class="mt-select w-auto">
                <option value="all">All agents</option>
                <option value="mine">My enquiries</option>
                <option value="unassigned">Unassigned</option>
            </select>
        </div>
        <div class="overflow-x-auto">
            <table class="mt-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Type</th>
                        <th>Destination</th>
                        <th>Status</th>
                        <th>Assigned</th>
                        <th>Date</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->enquiries as $enq)
                        <tr>
                            <td class="font-medium text-ink-900">{{ $enq->customer->name }}</td>
                            <td>{{ ucfirst($enq->enquiry_type) }}</td>
                            <td>{{ $enq->destination ?? '—' }}</td>
                            <td><x-status-pill :status="$enq->status" /></td>
                            <td>{{ $enq->assignedUser?->name ?? '—' }}</td>
                            <td class="text-ink-500">{{ $enq->created_at->format('d M Y') }}</td>
                            <td class="text-right">
                                <div class="inline-flex items-center gap-3">
                                    @if ($enq->converted_to_trip_id)
                                        <span class="inline-flex items-center gap-1 text-xs text-emerald-700 font-medium">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Converted
                                        </span>
                                    @endif
                                    <a href="{{ route('admin.enquiries.show', $enq->ulid) }}" class="text-brand-700 hover:text-brand-800 hover:underline text-sm font-medium">View</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-ink-400 py-8">No enquiries found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-ink-200/70">{{ $this->enquiries->links() }}</div>
    </div>
</div>
