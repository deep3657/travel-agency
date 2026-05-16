<div>
    <div class="bg-white rounded-lg shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destination</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Travel Dates</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submitted</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($this->enquiries as $e)
                        <tr>
                            <td class="px-4 py-3 text-sm">{{ ucfirst($e->enquiry_type) }}</td>
                            <td class="px-4 py-3 text-sm">{{ $e->destination ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $e->travel_from?->format('d M Y') ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @php $colors = ['new'=>'bg-blue-50 text-blue-700','in_progress'=>'bg-yellow-50 text-yellow-700','quoted'=>'bg-purple-50 text-purple-700','closed'=>'bg-gray-100 text-gray-600'] @endphp
                                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $colors[$e->status] ?? 'bg-gray-100' }}">{{ str_replace('_', ' ', ucfirst($e->status)) }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $e->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No enquiries yet. <a href="{{ route('packages.index') }}" class="text-blue-600 hover:underline">Browse packages</a> to get started.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t">{{ $this->enquiries->links() }}</div>
    </div>
</div>
