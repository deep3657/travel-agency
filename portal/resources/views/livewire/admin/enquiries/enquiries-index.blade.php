<div>
    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-4 border-b flex flex-wrap gap-3 items-center">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search enquiries..."
                class="border border-gray-300 rounded-md px-3 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select wire:model.live="filterStatus" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                <option value="all">All Statuses</option>
                <option value="new">New</option>
                <option value="in_progress">In Progress</option>
                <option value="quoted">Quoted</option>
                <option value="closed">Closed</option>
            </select>
            <select wire:model.live="filterAssigned" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                <option value="all">All Agents</option>
                <option value="mine">My Enquiries</option>
                <option value="unassigned">Unassigned</option>
            </select>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destination</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($this->enquiries as $enq)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $enq->customer->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ ucfirst($enq->enquiry_type) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $enq->destination ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium
                                    {{ $enq->status === 'new' ? 'bg-blue-100 text-blue-800' :
                                       ($enq->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' :
                                       ($enq->status === 'quoted' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-600')) }}">
                                    {{ str_replace('_', ' ', ucfirst($enq->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $enq->assignedUser?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $enq->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.enquiries.show', $enq->ulid) }}" class="text-blue-600 hover:text-blue-800 text-sm">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No enquiries found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t">{{ $this->enquiries->links() }}</div>
    </div>
</div>
