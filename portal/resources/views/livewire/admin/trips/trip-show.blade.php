<div class="space-y-6">
    {{-- Trip Overview --}}
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $trip->name }}</h2>
                <p class="text-gray-500">{{ $trip->customer->name }} · {{ $trip->primary_destination ?? 'Destination TBD' }}</p>
            </div>
            <span class="px-3 py-1 rounded text-sm font-medium bg-blue-100 text-blue-800">{{ ucfirst($trip->status) }}</span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div><dt class="text-gray-500">Travel Start</dt><dd>{{ $trip->travel_start?->format('d M Y') ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Travel End</dt><dd>{{ $trip->travel_end?->format('d M Y') ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Assigned Agent</dt><dd>{{ $trip->assignedUser?->name ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Quotations</dt><dd>{{ $trip->quotations->count() }}</dd></div>
        </div>
    </div>

    {{-- Quotations --}}
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-800">Quotations</h3>
            @can('create', \App\Models\Quotation::class)
                <button wire:click="createQuotation" class="px-4 py-2 bg-blue-700 text-white text-sm rounded-md hover:bg-blue-800">
                    + New Quotation
                </button>
            @endcan
        </div>
        @forelse($trip->quotations as $q)
            <div class="border rounded-lg p-4 mb-3">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="font-medium">Quotation #{{ $q->id }}</span>
                        <span class="ml-2 px-2 py-0.5 rounded text-xs {{ $q->status === 'accepted' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">{{ ucfirst($q->status) }}</span>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.quotations.editor', $q->ulid) }}" class="text-blue-600 hover:text-blue-800 text-sm">Edit</a>
                    </div>
                </div>
                @if($q->currentVersion)
                    <div class="mt-2 text-sm text-gray-600">
                        Version {{ $q->currentVersion->version_number }} · Grand Total: <strong>₹{{ number_format($q->currentVersion->grand_total?->toRupees() ?? 0) }}</strong>
                        @if($q->currentVersion->sent_at) · Sent {{ $q->currentVersion->sent_at->format('d M Y') }}@endif
                    </div>
                @endif
            </div>
        @empty
            <p class="text-sm text-gray-400">No quotations yet.</p>
        @endforelse
    </div>

    {{-- Bookings --}}
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-800">Bookings</h3>
            <a href="{{ route('admin.bookings.create') }}?trip_id={{ $trip->id }}" class="px-4 py-2 bg-gray-700 text-white text-sm rounded-md">+ New Booking</a>
        </div>
        @forelse($trip->bookings as $b)
            <div class="border rounded-lg p-4 mb-2 flex justify-between items-center">
                <div>
                    <span class="font-medium">{{ $b->booking_ref }}</span>
                    <span class="ml-2 text-sm text-gray-500">{{ ucfirst($b->booking_type) }}</span>
                    <span class="ml-2 px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-600">{{ $b->status }}</span>
                </div>
                <a href="{{ route('admin.bookings.show', $b->ulid) }}" class="text-blue-600 text-sm">View</a>
            </div>
        @empty
            <p class="text-sm text-gray-400">No bookings yet.</p>
        @endforelse
    </div>
</div>
