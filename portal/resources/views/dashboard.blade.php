@php
    $u = auth()->user();
    $greeting = match (true) {
        now()->hour < 12 => 'Good morning',
        now()->hour < 17 => 'Good afternoon',
        default          => 'Good evening',
    };
@endphp
<x-app-layout>
    <div class="py-8">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Hero greeting card --}}
            <section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-brand-700 via-brand-800 to-brand-900 text-white shadow-brand-glow animate-fade-in">
                {{-- Decorative pattern --}}
                <div aria-hidden="true" class="absolute inset-0 opacity-25 mix-blend-overlay">
                    <svg class="absolute -top-12 -right-12 h-72 w-72 text-white/40" viewBox="0 0 200 200" fill="none">
                        <circle cx="100" cy="100" r="90" stroke="currentColor" stroke-width="1.5"/>
                        <circle cx="100" cy="100" r="60" stroke="currentColor" stroke-width="1.5"/>
                        <circle cx="100" cy="100" r="30" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                    <svg class="absolute -bottom-10 -left-10 h-48 w-48 text-white/30" viewBox="0 0 200 200" fill="none">
                        <path d="M0 100 Q50 0 100 100 T200 100" stroke="currentColor" stroke-width="1.5" fill="none"/>
                        <path d="M0 130 Q50 30 100 130 T200 130" stroke="currentColor" stroke-width="1.5" fill="none"/>
                        <path d="M0 160 Q50 60 100 160 T200 160" stroke="currentColor" stroke-width="1.5" fill="none"/>
                    </svg>
                </div>
                <div class="relative px-6 sm:px-10 py-7 sm:py-9 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                    <div class="max-w-2xl">
                        <p class="text-[11px] uppercase tracking-[0.2em] text-brand-200/90 font-semibold">{{ now()->format('l, d M Y') }}</p>
                        <h1 class="mt-2 font-display text-3xl sm:text-4xl font-bold leading-[1.15]">
                            {{ $greeting }}, <span class="text-accent-300">{{ explode(' ', trim($u?->name ?? ''))[0] ?? 'there' }}</span>
                        </h1>
                        <p class="mt-2 text-sm sm:text-base text-brand-100/90">
                            Here's a snapshot of what's happening at Maruti Travels today. Let's make every journey memorable.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 shrink-0">
                        <a href="{{ route('admin.bookings.create') }}"
                           class="inline-flex items-center gap-2 rounded-lg bg-white text-brand-800 hover:bg-brand-50 px-4 py-2.5 text-sm font-semibold shadow-sm transition active:scale-[0.98]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            New Booking
                        </a>
                        <a href="{{ route('admin.customers.create') }}"
                           class="inline-flex items-center gap-2 rounded-lg bg-white/10 text-white hover:bg-white/20 border border-white/25 px-4 py-2.5 text-sm font-semibold backdrop-blur transition active:scale-[0.98]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                            New Customer
                        </a>
                    </div>
                </div>
            </section>
            <x-flash />

            {{-- Stat cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card
                    label="Total Customers"
                    :value="number_format($totalCustomers)"
                    tone="brand"
                    :href="route('admin.customers.index')"
                    icon="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-5a4 4 0 11-8 0 4 4 0 018 0zm6 3a3 3 0 11-6 0 3 3 0 016 0z" />

                <x-stat-card
                    label="Total Bookings"
                    :value="number_format($totalBookings)"
                    tone="emerald"
                    :href="route('admin.bookings.index')"
                    icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />

                <x-stat-card
                    label="Pending Enquiries"
                    :value="number_format($pendingEnquiries)"
                    tone="amber"
                    :href="route('admin.enquiries.index')"
                    icon="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />

                @if($isAdmin)
                    <x-stat-card
                        label="Monthly Revenue"
                        :value="'₹'.number_format($monthlyRevenue, 0)"
                        tone="violet"
                        icon="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                @else
                    <x-stat-card
                        label="My Enquiries"
                        :value="number_format($totalEnquiries)"
                        tone="sky"
                        icon="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                @endif
            </div>

            {{-- Two column section --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Recent Enquiries (2 cols) --}}
                <div class="lg:col-span-2 mt-card">
                    <div class="mt-card-header">
                        <div>
                            <h3 class="font-semibold text-ink-900">Recent Enquiries</h3>
                            <p class="text-xs text-ink-500 mt-0.5">Latest customer interest, newest first</p>
                        </div>
                        <a href="{{ route('admin.enquiries.index') }}" class="text-sm font-medium text-brand-700 hover:text-brand-800">View all →</a>
                    </div>
                    <div class="overflow-x-auto">
                        @if ($recentEnquiries->isEmpty())
                            <x-empty-state title="No enquiries yet"
                                description="When customers reach out — by phone, email or signup — their enquiries will land here." />
                        @else
                            <table class="mt-table">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Type</th>
                                        <th>Destination</th>
                                        <th>Status</th>
                                        <th class="text-right">Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentEnquiries as $e)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.enquiries.show', $e->ulid) }}" class="font-medium text-ink-900 hover:text-brand-700">
                                                    {{ $e->customer?->name ?? '—' }}
                                                </a>
                                            </td>
                                            <td class="capitalize">{{ $e->enquiry_type }}</td>
                                            <td>{{ $e->destination ?? '—' }}</td>
                                            <td><x-status-pill :status="$e->status" /></td>
                                            <td class="text-right text-xs text-ink-500">{{ $e->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="space-y-6">
                    <div class="mt-card">
                        <div class="mt-card-header">
                            <h3 class="font-semibold text-ink-900">Quick actions</h3>
                        </div>
                        <div class="p-3 grid grid-cols-1 gap-1">
                            <a href="{{ route('admin.enquiries.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-ink-50 transition group">
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-700 group-hover:bg-brand-100">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                </span>
                                <span class="flex-1">
                                    <span class="block text-sm font-medium text-ink-800">Triage enquiries</span>
                                    <span class="block text-xs text-ink-500">Reply, qualify and convert leads</span>
                                </span>
                            </a>
                            <a href="{{ route('admin.trips.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-ink-50 transition group">
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700 group-hover:bg-emerald-100">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                </span>
                                <span class="flex-1">
                                    <span class="block text-sm font-medium text-ink-800">Plan a trip</span>
                                    <span class="block text-xs text-ink-500">Build itinerary & quotation</span>
                                </span>
                            </a>
                            <a href="{{ route('admin.bookings.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-ink-50 transition group">
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 text-amber-700 group-hover:bg-amber-100">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </span>
                                <span class="flex-1">
                                    <span class="block text-sm font-medium text-ink-800">Confirm a booking</span>
                                    <span class="block text-xs text-ink-500">Capture an offline confirmation</span>
                                </span>
                            </a>
                            <a href="{{ route('admin.supplier-docs.new') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-ink-50 transition group">
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-violet-50 text-violet-700 group-hover:bg-violet-100">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                </span>
                                <span class="flex-1">
                                    <span class="block text-sm font-medium text-ink-800">Upload supplier doc</span>
                                    <span class="block text-xs text-ink-500">AI extract & convert to standard</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Upcoming travel --}}
            @if($upcomingTrips->isNotEmpty())
                <div class="mt-card">
                    <div class="mt-card-header">
                        <div>
                            <h3 class="font-semibold text-ink-900">Upcoming travel</h3>
                            <p class="text-xs text-ink-500 mt-0.5">Trips departing in the next 7 days</p>
                        </div>
                        <a href="{{ route('admin.trips.index') }}" class="text-sm font-medium text-brand-700 hover:text-brand-800">All trips →</a>
                    </div>
                    <ul class="divide-y divide-ink-100">
                        @foreach($upcomingTrips as $trip)
                            <li class="flex items-center justify-between gap-4 px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                    </span>
                                    <div>
                                        <a href="{{ route('admin.trips.show', $trip->ulid) }}" class="text-sm font-semibold text-ink-900 hover:text-brand-700">{{ $trip->name }}</a>
                                        <div class="text-xs text-ink-500">{{ $trip->customer?->name }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-ink-800">{{ $trip->travel_start->format('d M Y') }}</div>
                                    <div class="text-xs text-ink-500">{{ $trip->travel_start->diffForHumans() }}</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
