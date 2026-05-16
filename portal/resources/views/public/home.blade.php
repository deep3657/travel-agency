<x-public-layout title="Welcome">

    {{-- Hero --}}
    <section class="bg-gradient-to-br from-[#0F4C81] to-blue-700 text-white py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Discover Your Perfect Journey</h1>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">Expert travel planning for unforgettable experiences across India and beyond.</p>
            <a href="{{ route('packages.index') }}" class="inline-block bg-white text-[#0F4C81] font-bold px-8 py-3 rounded-lg hover:bg-blue-50 transition">
                Explore Packages
            </a>
        </div>
    </section>

    {{-- Featured Packages --}}
    @if($featuredPackages->isNotEmpty())
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Featured Packages</h2>
            <p class="text-gray-500 mb-8">Handpicked destinations for every kind of traveller.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredPackages as $pkg)
                <a href="{{ route('packages.show', $pkg->slug) }}" class="group bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden border border-gray-100">
                    @if($pkg->hero_image_path)
                        <div class="h-48 bg-cover bg-center" style="background-image: url('{{ $pkg->hero_image_path }}')"></div>
                    @else
                        <div class="h-48 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center text-blue-400">
                            <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                        </div>
                    @endif
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded">{{ $pkg->duration_days }}D/{{ $pkg->duration_nights }}N</span>
                            <span class="text-sm font-bold text-gray-900">From ₹{{ number_format($pkg->price_from_inr->toRupees()) }}</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-[#0F4C81] transition">{{ $pkg->title }}</h3>
                        <p class="text-sm text-gray-500">{{ $pkg->destinations }}</p>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('packages.index') }}" class="btn-primary inline-block">View All Packages</a>
            </div>
        </div>
    </section>
    @endif

    {{-- CTA --}}
    <section class="bg-[#0F4C81] text-white py-16">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to Plan Your Dream Trip?</h2>
            <p class="text-blue-100 mb-8">Contact our travel experts today and get a personalised quotation.</p>
            <a href="{{ route('contact') }}" class="inline-block bg-white text-[#0F4C81] font-bold px-8 py-3 rounded-lg hover:bg-blue-50 transition">
                Contact Us
            </a>
        </div>
    </section>

</x-public-layout>
