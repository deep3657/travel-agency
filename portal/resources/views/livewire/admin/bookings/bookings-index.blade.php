<div>
    <div class="mt-card">
        <div class="mt-card-header flex-wrap gap-3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search ref, customer..." class="mt-input w-64">
            <select wire:model.live="filterStatus" class="mt-select w-auto">
                <option value="all">All statuses</option>
                <option value="pending_confirmation">Pending confirmation</option>
                <option value="confirmed">Confirmed</option>
                <option value="cancelled">Cancelled</option>
                <option value="completed">Completed</option>
            </select>
            <select wire:model.live="filterType" class="mt-select w-auto">
                <option value="all">All types</option>
                <option value="flight">Flight</option>
                <option value="hotel">Hotel</option>
                <option value="package">Package</option>
            </select>
        </div>
        <div class="overflow-x-auto">
            <table class="mt-table">
                <thead>
                    <tr>
                        <th>Ref</th>
                        <th>Customer</th>
                        <th>Type</th>
                        <th>Vendor</th>
                        @if($this->showFinancials)<th>Sale</th>@endif
                        <th>Status</th>
                        <th>Payment</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->bookings as $b)
                        <tr>
                            <td class="font-mono font-medium text-ink-900">{{ $b->booking_ref }}</td>
                            <td>{{ $b->customer->name }}</td>
                            <td>{{ ucfirst($b->booking_type) }}</td>
                            <td>{{ $b->vendor?->name ?? '—' }}</td>
                            @if($this->showFinancials)<td>₹{{ number_format($b->sale_amount?->toRupees() ?? 0) }}</td>@endif
                            <td><x-status-pill :status="$b->status" /></td>
                            <td><x-status-pill :status="$b->payment_status" /></td>
                            <td class="text-right"><a href="{{ route('admin.bookings.show', $b->ulid) }}" class="text-brand-700 hover:text-brand-800 hover:underline text-sm">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-ink-400 py-8">No bookings found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-ink-200/70">{{ $this->bookings->links() }}</div>
    </div>
</div>
