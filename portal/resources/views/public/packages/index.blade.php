<x-public-layout title="Travel Packages">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Travel Packages</h1>
        <p class="text-gray-500 mb-8">Explore our curated selection of holiday packages.</p>

        {{-- Search --}}
        <form method="GET" class="mb-8 flex gap-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search packages..."
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0F4C81]">
            <button type="submit" class="btn-primary">Search</button>
        </form>

        @if($packages->isEmpty())
            <div class="text-center py-16 text-gray-400">
                <p class="text-xl">No packages found.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($packages as $pkg)
                <a href="{{ route('packages.show', $pkg->slug) }}" class="group bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden border border-gray-100">
                    @if($pkg->hero_image_path)
                        <div class="h-48 bg-cover bg-center" style="background-image: url('{{ $pkg->hero_image_path }}')"></div>
                    @else
                        <div class="h-48 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center text-blue-300">
                            <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        </div>
                    @endif
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded">{{ $pkg->duration_days }}D / {{ $pkg->duration_nights }}N</span>
                            <span class="text-sm font-bold text-gray-900">From ₹{{ number_format($pkg->price_from_inr->toRupees()) }}</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-[#0F4C81] mb-1">{{ $pkg->title }}</h3>
                        <p class="text-sm text-gray-500 mb-2">📍 {{ $pkg->destinations }}</p>
                        @if($pkg->short_description)
                            <p class="text-sm text-gray-400 line-clamp-2">{{ $pkg->short_description }}</p>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $packages->links() }}
            </div>
        @endif
    </div>

</x-public-layout>
