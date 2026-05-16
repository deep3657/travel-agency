<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $trip->name }}</h2>
                <p class="text-gray-500">{{ $trip->primary_destination ?? 'Destination TBD' }}</p>
            </div>
            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded text-sm font-medium">{{ ucfirst($trip->status) }}</span>
        </div>
        @if($trip->travel_start)
            <div class="mt-3 text-sm text-gray-600">
                Travel: {{ $trip->travel_start->format('d M Y') }} – {{ $trip->travel_end?->format('d M Y') ?? 'TBD' }}
            </div>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="font-semibold mb-4">Bookings</h3>
        @forelse($trip->bookings as $booking)
            <div class="border rounded-lg p-4 mb-3">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="font-mono font-medium">{{ $booking->booking_ref }}</span>
                        <span class="ml-2 text-sm text-gray-500">{{ ucfirst($booking->booking_type) }}</span>
                        <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs">{{ $booking->status }}</span>
                    </div>
                </div>
                {{-- Voucher downloads --}}
                @if($booking->documents->isNotEmpty())
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach($booking->documents as $doc)
                            <a href="{{ URL::signedRoute('files.download', ['token' => encrypt($doc->ulid)]) }}"
                               class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded text-xs hover:bg-blue-100">
                                ↓ {{ ucfirst(str_replace('_', ' ', $doc->doc_type)) }}
                            </a>
                        @endforeach
                    </div>
                @endif
                {{-- Cancellation --}}
                @if(in_array($booking->status, ['confirmed', 'pending_confirmation']))
                    <div class="mt-3 pt-3 border-t">
                        <form method="POST" action="{{ route('customer.bookings.cancellation', $booking->ulid) }}"
                              onsubmit="return confirm('Are you sure you want to request cancellation?')">
                            @csrf
                            <input type="hidden" name="reason" value="Customer requested cancellation">
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Request Cancellation</button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <p class="text-sm text-gray-400">No bookings yet for this trip.</p>
        @endforelse
    </div>
</div>
