<x-public-layout title="Welcome">

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-hero-gradient text-white">
        <div class="absolute inset-0 opacity-20" aria-hidden="true">
            <svg class="w-full h-full" viewBox="0 0 1200 600" preserveAspectRatio="none">
                <defs>
                    <linearGradient id="orb" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="white" stop-opacity="0.4"/>
                        <stop offset="100%" stop-color="white" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <circle cx="200" cy="120" r="220" fill="url(#orb)"/>
                <circle cx="1000" cy="500" r="280" fill="url(#orb)"/>
            </svg>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28">
            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 ring-1 ring-white/30 text-xs font-medium backdrop-blur">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-accent-400"></span>
                    Curated journeys, trusted advisors
                </span>
                <h1 class="font-display mt-5 text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight">
                    Discover your<br><span class="text-accent-300">perfect journey.</span>
                </h1>
                <p class="mt-5 text-lg sm:text-xl text-brand-100 max-w-2xl">
                    From Himalayan retreats to European getaways — we craft seamless travel experiences with
                    transparent pricing and end-to-end support.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="{{ route('packages.index') }}" class="mt-btn-lg bg-white text-brand-800 hover:bg-brand-50 shadow-lg">
                        Explore Packages
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="{{ route('contact') }}" class="mt-btn-lg bg-transparent text-white ring-1 ring-white/40 hover:bg-white/10">
                        Talk to an expert
                    </a>
                </div>
            </div>
        </div>

        {{-- Trust strip --}}
        <div class="relative bg-white/5 backdrop-blur-sm border-t border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
                <div>
                    <div class="text-2xl sm:text-3xl font-bold text-white">5,000+</div>
                    <div class="text-xs uppercase tracking-wide text-brand-200 mt-1">Happy travellers</div>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold text-white">200+</div>
                    <div class="text-xs uppercase tracking-wide text-brand-200 mt-1">Destinations</div>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold text-white">19+</div>
                    <div class="text-xs uppercase tracking-wide text-brand-200 mt-1">Years of trust</div>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold text-white">24×7</div>
                    <div class="text-xs uppercase tracking-wide text-brand-200 mt-1">Travel support</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured Packages --}}
    @if($featuredPackages->isNotEmpty())
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between flex-wrap gap-4 mb-10">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-brand-700">Featured</span>
                        <h2 class="font-display text-3xl sm:text-4xl font-bold text-ink-900 mt-2">Hand-picked journeys</h2>
                        <p class="text-ink-500 mt-1">Curated by our experts for memorable getaways.</p>
                    </div>
                    <a href="{{ route('packages.index') }}" class="text-sm font-semibold text-brand-700 hover:text-brand-800">
                        View all packages →
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featuredPackages as $pkg)
                        <a href="{{ route('packages.show', $pkg->slug) }}"
                           class="group mt-card-hover overflow-hidden">
                            <div class="relative h-52 overflow-hidden">
                                @if($pkg->hero_image_path)
                                    <img src="{{ $pkg->hero_image_path }}" alt="{{ $pkg->title }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="h-full bg-gradient-to-br from-brand-100 via-brand-200 to-brand-300 flex items-center justify-center">
                                        <svg class="h-16 w-16 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                                    </div>
                                @endif
                                <div class="absolute top-3 left-3">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white/95 text-xs font-semibold text-brand-700 shadow-sm">
                                        {{ $pkg->duration_days }}D · {{ $pkg->duration_nights }}N
                                    </span>
                                </div>
                            </div>
                            <div class="p-5">
                                <h3 class="font-display text-lg font-semibold text-ink-900 group-hover:text-brand-700 transition mt-line-clamp-2">{{ $pkg->title }}</h3>
                                <p class="text-sm text-ink-500 mt-1.5 inline-flex items-center gap-1">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.66 16.66L13.41 20.9a2 2 0 01-2.83 0l-4.24-4.24a8 8 0 1111.31 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $pkg->destinations }}
                                </p>
                                <div class="flex items-end justify-between mt-4 pt-4 border-t border-ink-100">
                                    <div>
                                        <span class="text-xs text-ink-500 block">From</span>
                                        <span class="text-lg font-bold text-ink-900">₹{{ number_format($pkg->price_from_inr->toRupees()) }}</span>
                                    </div>
                                    <span class="text-xs font-semibold text-brand-700 group-hover:translate-x-1 transition-transform">View details →</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Why us --}}
    <section class="py-20 bg-ink-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs font-semibold uppercase tracking-wider text-brand-700">Why Maruti Travels</span>
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-ink-900 mt-2">Travel, the way you deserve</h2>
                <p class="text-ink-500 mt-3">Every detail thoughtfully arranged so you can simply enjoy the trip.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ([
                    ['Expert advisors', 'Decades of travel know-how — we tailor every journey to your style and budget.', 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ['Transparent pricing', 'No hidden fees, no surprises. GST-compliant invoicing and clear quotations.', 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                    ['24×7 travel support', 'On-trip support whenever you need us — flight changes, hotel issues, anything.', 'M18.364 5.636a9 9 0 010 12.728M15.536 8.464a5 5 0 010 7.072M3 3l3.59 3.59a3 3 0 003.41-1.41l4.59-1.59a3 3 0 011.41 0l4.59 1.59a3 3 0 003.41 1.41L21 3'],
                ] as [$title, $desc, $iconPath])
                    <div class="mt-card mt-card-body">
                        <div class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-700 mb-4">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="{{ $iconPath }}"/></svg>
                        </div>
                        <h3 class="font-display text-lg font-semibold text-ink-900">{{ $title }}</h3>
                        <p class="text-sm text-ink-600 mt-2">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-hero-gradient text-white py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display text-3xl sm:text-4xl font-bold">Ready to plan your dream trip?</h2>
            <p class="text-brand-100 mt-3 text-lg">Tell us where you'd like to go, we'll handle the rest.</p>
            <div class="mt-7 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('contact') }}" class="mt-btn-lg bg-white text-brand-800 hover:bg-brand-50 shadow-lg">
                    Get a free quotation
                </a>
                <a href="{{ route('customer.register') }}" class="mt-btn-lg bg-transparent text-white ring-1 ring-white/40 hover:bg-white/10">
                    Create an account
                </a>
            </div>
        </div>
    </section>

</x-public-layout>
