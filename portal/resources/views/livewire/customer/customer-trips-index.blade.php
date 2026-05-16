<div>
    <div class="space-y-3">
        @forelse($this->trips as $trip)
            <a href="{{ route('customer.trips.show', $trip->ulid) }}" class="block bg-white rounded-lg shadow-sm p-5 hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $trip->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $trip->primary_destination ?? 'Destination TBD' }}</p>
                    </div>
                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded text-xs font-medium">{{ ucfirst($trip->status) }}</span>
                </div>
                @if($trip->travel_start)
                    <p class="text-sm text-gray-500 mt-2">{{ $trip->travel_start->format('d M Y') }} – {{ $trip->travel_end?->format('d M Y') ?? '' }}</p>
                @endif
            </a>
        @empty
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <p class="text-gray-400">No trips yet. Contact us or <a href="{{ route('packages.index') }}" class="text-blue-600 hover:underline">browse packages</a> to plan your first trip!</p>
            </div>
        @endforelse
        <div class="mt-2">{{ $this->trips->links() }}</div>
    </div>
</div>
