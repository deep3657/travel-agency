<div class="space-y-6">
    {{-- Welcome banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-hero-gradient text-white p-6 sm:p-8">
        <div class="absolute inset-0 opacity-15 pointer-events-none" aria-hidden="true">
            <svg class="w-full h-full" viewBox="0 0 800 300" preserveAspectRatio="none">
                <circle cx="700" cy="60" r="120" fill="white"/>
                <circle cx="120" cy="240" r="100" fill="white"/>
            </svg>
        </div>
        <div class="relative">
            <p class="text-xs uppercase tracking-wider text-brand-200">Welcome back</p>
            <h1 class="font-display text-3xl sm:text-4xl font-bold mt-1">Hello, {{ $customerName }} 👋</h1>
            <p class="text-brand-100 mt-2 max-w-xl">Track your enquiries, view your upcoming trips and manage your profile from one place.</p>
            <div class="mt-5 flex flex-wrap gap-3">
                <a href="{{ route('packages.index') }}" class="mt-btn bg-white text-brand-800 hover:bg-brand-50">
                    Browse packages
                </a>
                <a href="{{ route('customer.enquiries') }}#enquiry_type" class="mt-btn bg-white/15 text-white ring-1 ring-white/30 hover:bg-white/20">
                    Submit a new enquiry
                </a>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <a href="{{ route('customer.enquiries') }}" class="mt-stat group">
            <div class="flex items-start justify-between">
                <span class="mt-stat-label">My enquiries</span>
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </span>
            </div>
            <div class="flex items-end justify-between">
                <span class="mt-stat-value">{{ number_format($enquiryCount) }}</span>
                <span class="text-xs text-brand-700 font-medium group-hover:translate-x-0.5 transition">View all →</span>
            </div>
        </a>
        <a href="{{ route('customer.trips') }}" class="mt-stat group">
            <div class="flex items-start justify-between">
                <span class="mt-stat-label">My trips</span>
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                </span>
            </div>
            <div class="flex items-end justify-between">
                <span class="mt-stat-value">{{ number_format($tripCount) }}</span>
                <span class="text-xs text-emerald-700 font-medium group-hover:translate-x-0.5 transition">View all →</span>
            </div>
        </a>
    </div>

    {{-- Upcoming trip --}}
    @if($upcomingTrip)
        <div class="mt-card">
            <div class="mt-card-header">
                <h3 class="font-semibold text-ink-900">Your next trip</h3>
                <a href="{{ route('customer.trips.show', $upcomingTrip->ulid) }}" class="text-sm font-medium text-brand-700 hover:text-brand-800">View details →</a>
            </div>
            <div class="mt-card-body flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h4 class="font-display text-lg font-semibold text-ink-900">{{ $upcomingTrip->name }}</h4>
                    @if($upcomingTrip->destination)
                        <p class="text-sm text-ink-500 mt-0.5 inline-flex items-center gap-1">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.66 16.66L13.41 20.9a2 2 0 01-2.83 0l-4.24-4.24a8 8 0 1111.31 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $upcomingTrip->destination }}
                        </p>
                    @endif
                </div>
                <div class="text-right">
                    <div class="font-display text-xl font-bold text-brand-700">{{ $upcomingTrip->travel_start->format('d M Y') }}</div>
                    <div class="text-xs text-ink-500">{{ $upcomingTrip->travel_start->diffForHumans() }}</div>
                </div>
            </div>
        </div>
    @endif

    {{-- Quick actions --}}
    <div class="mt-card">
        <div class="mt-card-header">
            <h3 class="font-semibold text-ink-900">Quick actions</h3>
        </div>
        <div class="p-3 grid grid-cols-1 sm:grid-cols-3 gap-2">
            @foreach ([
                ['Browse packages',  route('packages.index'),    'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'],
                ['Update profile',   route('customer.profile'),  'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ['Contact support',  route('contact'),           'M3 5a2 2 0 012-2h2.28a2 2 0 011.95 1.55l.7 3.06a2 2 0 01-1.06 2.21l-1.43.71a11 11 0 005.06 5.06l.71-1.43a2 2 0 012.21-1.06l3.06.7A2 2 0 0121 16.72V19a2 2 0 01-2 2h-1C9.72 21 3 14.28 3 6V5z'],
            ] as [$label, $href, $iconPath])
                <a href="{{ $href }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-ink-50 transition">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $iconPath }}"/></svg>
                    </span>
                    <span class="text-sm font-medium text-ink-800">{{ $label }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
