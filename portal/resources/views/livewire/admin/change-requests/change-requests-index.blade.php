<div>
    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-4 border-b flex gap-3">
            <select wire:model.live="filterStatus" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                <option value="all">All Statuses</option>
                <option value="open">Open</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requested By</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($this->changeRequests as $cr)
                        <tr>
                            <td class="px-4 py-3 font-mono text-sm">{{ $cr->booking->booking_ref }}</td>
                            <td class="px-4 py-3 text-sm">{{ ucfirst($cr->request_type) }}</td>
                            <td class="px-4 py-3 text-sm">{{ ucfirst($cr->requested_by) }}</td>
                            <td class="px-4 py-3">
                                @php $colors = ['open'=>'bg-yellow-50 text-yellow-700','in_progress'=>'bg-blue-50 text-blue-700','completed'=>'bg-green-50 text-green-700'] @endphp
                                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $colors[$cr->status] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst(str_replace('_', ' ', $cr->status)) }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $cr->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-right"><a href="{{ route('admin.change-requests.edit', $cr->ulid) }}" class="text-blue-600 hover:text-blue-800 text-sm">Edit</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No change requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t">{{ $this->changeRequests->links() }}</div>
    </div>
</div>
