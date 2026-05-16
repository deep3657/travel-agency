<div>
    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-4 border-b flex flex-wrap gap-3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search ref, customer..."
                class="border border-gray-300 rounded-md px-3 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select wire:model.live="filterStatus" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                <option value="all">All Statuses</option>
                <option value="pending_confirmation">Pending Confirmation</option>
                <option value="confirmed">Confirmed</option>
                <option value="cancelled">Cancelled</option>
                <option value="completed">Completed</option>
            </select>
            <select wire:model.live="filterType" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                <option value="all">All Types</option>
                <option value="flight">Flight</option>
                <option value="hotel">Hotel</option>
                <option value="package">Package</option>
            </select>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ref</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
                        @if($this->showFinancials)<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sale</th>@endif
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($this->bookings as $b)
                        <tr>
                            <td class="px-4 py-3 font-mono text-sm font-medium">{{ $b->booking_ref }}</td>
                            <td class="px-4 py-3 text-sm">{{ $b->customer->name }}</td>
                            <td class="px-4 py-3 text-sm">{{ ucfirst($b->booking_type) }}</td>
                            <td class="px-4 py-3 text-sm">{{ $b->vendor?->name ?? '—' }}</td>
                            @if($this->showFinancials)<td class="px-4 py-3 text-sm">₹{{ number_format($b->sale_amount?->toRupees() ?? 0) }}</td>@endif
                            <td class="px-4 py-3">
                                @php $statusColors = ['confirmed'=>'bg-green-50 text-green-700','pending_confirmation'=>'bg-yellow-50 text-yellow-700','cancelled'=>'bg-red-50 text-red-700','completed'=>'bg-blue-50 text-blue-700'] @endphp
                                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $statusColors[$b->status] ?? 'bg-gray-100 text-gray-600' }}">{{ str_replace('_', ' ', ucfirst($b->status)) }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ ucfirst($b->payment_status) }}</td>
                            <td class="px-4 py-3 text-right"><a href="{{ route('admin.bookings.show', $b->ulid) }}" class="text-blue-600 hover:text-blue-800 text-sm">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">No bookings found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t">{{ $this->bookings->links() }}</div>
    </div>
</div>
