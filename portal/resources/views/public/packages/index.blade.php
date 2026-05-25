<x-public-layout title="Travel Packages">
    {{-- Header band --}}
    <section class="bg-hero-gradient text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
            <span class="text-xs font-semibold uppercase tracking-wider text-brand-200">Curated holidays</span>
            <h1 class="font-display text-3xl sm:text-4xl font-bold mt-2">Travel packages</h1>
            <p class="text-brand-100 mt-2 max-w-2xl">Browse hand-picked itineraries across India and abroad. All prices are per person, GST extra.</p>

            <form method="GET" class="mt-7 max-w-xl flex gap-2 bg-white/10 backdrop-blur p-1.5 rounded-xl ring-1 ring-white/20">
                <div class="flex-1 flex items-center gap-2 bg-white rounded-lg px-3">
                    <svg class="h-5 w-5 text-ink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by destination, e.g. Goa, Kerala, Bali"
                        class="flex-1 border-0 focus:ring-0 text-sm py-2.5 placeholder:text-ink-400">
                </div>
                <button type="submit" class="mt-btn-accent">
                    Search
                </button>
            </form>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($packages->isEmpty())
            <x-empty-state
                title="No packages match your search"
                description="Try a broader keyword, or {{ '' }}browse our full catalogue.">
                <a href="{{ route('packages.index') }}" class="mt-btn-secondary mt-btn-sm">Clear search</a>
            </x-empty-state>
        @else
            <p class="text-sm text-ink-500 mb-6">{{ $packages->total() }} package{{ $packages->total() === 1 ? '' : 's' }} found</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($packages as $pkg)
                    <a href="{{ route('packages.show', $pkg->slug) }}" class="group mt-card-hover overflow-hidden">
                        <div class="relative h-52 overflow-hidden">
                            @if($pkg->hero_image_path)
                                <img src="{{ $pkg->hero_image_path }}" alt="{{ $pkg->title }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="h-full bg-gradient-to-br from-brand-100 via-brand-200 to-brand-300 flex items-center justify-center">
                                    <svg class="h-16 w-16 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
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
                            @if($pkg->short_description)
                                <p class="text-sm text-ink-500 mt-2 mt-line-clamp-2">{{ $pkg->short_description }}</p>
                            @endif
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

            <div class="mt-10">
                {{ $packages->links() }}
            </div>
        @endif
    </div>
</x-public-layout>
