<div>
    <div class="mt-card">
        <div class="mt-card-header flex-wrap gap-3">
            <select wire:model.live="filterStatus" class="mt-select w-auto">
                <option value="all">All statuses</option>
                <option value="open">Open</option>
                <option value="in_progress">In progress</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <div class="overflow-x-auto">
            <table class="mt-table">
                <thead>
                    <tr>
                        <th>Booking</th>
                        <th>Type</th>
                        <th>Requested by</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->changeRequests as $cr)
                        <tr>
                            <td class="font-mono">{{ $cr->booking->booking_ref }}</td>
                            <td>{{ ucfirst($cr->request_type) }}</td>
                            <td>{{ ucfirst($cr->requested_by) }}</td>
                            <td><x-status-pill :status="$cr->status" /></td>
                            <td class="text-ink-500">{{ $cr->created_at->format('d M Y') }}</td>
                            <td class="text-right"><a href="{{ route('admin.change-requests.edit', $cr->ulid) }}" class="text-brand-700 hover:text-brand-800 hover:underline text-sm">Edit</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-ink-400 py-8">No change requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-ink-200/70">{{ $this->changeRequests->links() }}</div>
    </div>
</div>
