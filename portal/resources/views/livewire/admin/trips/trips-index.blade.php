<div>
    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-4 border-b flex gap-3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search trips..."
                class="border border-gray-300 rounded-md px-3 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select wire:model.live="filterStatus" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                <option value="all">All Statuses</option>
                <option value="planning">Planning</option>
                <option value="confirmed">Confirmed</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trip</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destination</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Travel Dates</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Agent</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($this->trips as $trip)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $trip->name }}</td>
                            <td class="px-4 py-3 text-sm">{{ $trip->customer->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $trip->primary_destination ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $trip->travel_start?->format('d M') ?? '—' }} – {{ $trip->travel_end?->format('d M Y') ?? '' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">{{ ucfirst($trip->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $trip->assignedUser?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-right"><a href="{{ route('admin.trips.show', $trip->ulid) }}" class="text-blue-600 hover:text-blue-800 text-sm">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No trips found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t">{{ $this->trips->links() }}</div>
    </div>
</div>
