<div>
    <x-page-header title="My Trips" subtitle="Track your upcoming and past journeys with Maruti Travels." />

    @if($this->trips->isEmpty())
        <div class="mt-card">
            <x-empty-state
                title="No trips yet"
                description="Once we plan a trip together, it'll show up here with all your bookings and documents.">
                <a href="{{ route('packages.index') }}" class="mt-btn-primary mt-btn-sm">Browse packages</a>
            </x-empty-state>
        </div>
    @else
        <div class="space-y-3">
            @foreach($this->trips as $trip)
                <a href="{{ route('customer.trips.show', $trip->ulid) }}" class="block mt-card-hover">
                    <div class="mt-card-body flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-brand-50 text-brand-700 shrink-0">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                            </span>
                            <div>
                                <h3 class="font-semibold text-ink-900">{{ $trip->name }}</h3>
                                <p class="text-sm text-ink-500 mt-0.5">{{ $trip->primary_destination ?? 'Destination TBD' }}</p>
                                @if($trip->travel_start)
                                    <p class="text-xs text-ink-500 mt-1">
                                        {{ $trip->travel_start->format('d M Y') }}
                                        @if($trip->travel_end) – {{ $trip->travel_end->format('d M Y') }}@endif
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <x-status-pill :status="$trip->status" />
                            <span class="text-brand-700 text-sm">→</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-6">{{ $this->trips->links() }}</div>
    @endif
</div>
