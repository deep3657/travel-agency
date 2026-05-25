<div class="space-y-6">
    <div>
        <a href="{{ route('customer.trips') }}" class="text-sm text-ink-500 hover:text-ink-700 inline-flex items-center gap-1">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to my trips
        </a>
    </div>

    {{-- Trip overview --}}
    <div class="mt-card overflow-hidden">
        <div class="bg-hero-gradient px-6 py-6 text-white">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                <div>
                    <p class="text-xs uppercase tracking-wider text-brand-200">Trip</p>
                    <h2 class="font-display text-2xl font-bold mt-1">{{ $trip->name }}</h2>
                    <p class="text-brand-100 text-sm mt-1.5 inline-flex items-center gap-1">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.66 16.66L13.41 20.9a2 2 0 01-2.83 0l-4.24-4.24a8 8 0 1111.31 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $trip->primary_destination ?? 'Destination TBD' }}
                    </p>
                </div>
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/15 ring-1 ring-white/30 text-sm font-medium backdrop-blur capitalize">
                    {{ str_replace('_', ' ', $trip->status) }}
                </span>
            </div>
            @if($trip->travel_start)
                <div class="mt-5 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-brand-100">
                    <span class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $trip->travel_start->format('d M Y') }}
                        @if($trip->travel_end) – {{ $trip->travel_end->format('d M Y') }}@endif
                    </span>
                </div>
            @endif
        </div>
    </div>

    {{-- Bookings --}}
    <div class="mt-card">
        <div class="mt-card-header">
            <div>
                <h3 class="font-semibold text-ink-900">Bookings &amp; documents</h3>
                <p class="text-xs text-ink-500 mt-0.5">Confirmed bookings and downloadable vouchers for this trip.</p>
            </div>
        </div>
        <div class="p-5 space-y-3">
            @forelse($trip->bookings as $booking)
                <div class="rounded-xl border border-ink-200/70 p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </span>
                            <div>
                                <div class="font-mono font-semibold text-ink-900">{{ $booking->booking_ref }}</div>
                                <div class="text-xs text-ink-500 capitalize">{{ str_replace('_', ' ', $booking->booking_type) }}</div>
                            </div>
                        </div>
                        <x-status-pill :status="$booking->status" />
                    </div>

                    @php
                        // Only show the latest (current) version of each document type
                        // to customers — they don't need to see archived superseded
                        // copies, only the up-to-date voucher.
                        $currentDocs = $booking->documents
                            ->sortByDesc('version_number')
                            ->unique('doc_type')
                            ->values();
                    @endphp
                    @if($currentDocs->isNotEmpty())
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach($currentDocs as $doc)
                                <a href="{{ URL::signedRoute('files.download', ['token' => $doc->ulid]) }}"
                                   class="mt-btn-secondary mt-btn-sm">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
                                    {{ ucwords(str_replace('_', ' ', $doc->doc_type)) }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if(in_array($booking->status, ['confirmed', 'pending_confirmation']))
                        <form method="POST" action="{{ route('customer.bookings.cancellation', $booking->ulid) }}"
                              onsubmit="return confirm('Are you sure you want to request cancellation? Our team will be in touch.')"
                              class="mt-4 pt-4 border-t border-ink-100">
                            @csrf
                            <input type="hidden" name="reason" value="Customer requested cancellation">
                            <button type="submit" class="text-rose-600 hover:text-rose-700 text-sm font-medium inline-flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22"/></svg>
                                Request cancellation
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <x-empty-state
                    title="No bookings yet"
                    description="Once a booking is confirmed for this trip, you'll see it here with vouchers." />
            @endforelse
        </div>
    </div>
</div>
