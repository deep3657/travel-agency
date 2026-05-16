<x-public-layout :title="$package->seo_meta_title ?? $package->title" :seoDescription="$package->seo_meta_description ?? $package->short_description">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2">
                @if($package->hero_image_path)
                    <img src="{{ $package->hero_image_path }}" alt="{{ $package->title }}" class="w-full h-64 object-cover rounded-xl mb-6">
                @endif

                <div class="flex flex-wrap gap-2 mb-3">
                    @foreach($package->category_tags ?? [] as $tag)
                        <span class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded">{{ $tag }}</span>
                    @endforeach
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $package->title }}</h1>
                <p class="text-gray-500 mb-4">📍 {{ $package->destinations }}
                    @if($package->departure_city) · Departs from {{ $package->departure_city }}@endif
                </p>

                @if($package->short_description)
                    <p class="text-lg text-gray-600 mb-6">{{ $package->short_description }}</p>
                @endif

                @if($package->highlights)
                    <h2 class="text-xl font-semibold mb-3">Highlights</h2>
                    <ul class="list-disc list-inside text-gray-600 space-y-1 mb-6">
                        @foreach($package->highlights as $h)
                            <li>{{ $h }}</li>
                        @endforeach
                    </ul>
                @endif

                @if($package->long_description)
                    <h2 class="text-xl font-semibold mb-3">About this Package</h2>
                    <div class="prose text-gray-600 mb-6">{{ $package->long_description }}</div>
                @endif

                {{-- Itinerary --}}
                @if($package->itineraryDays->isNotEmpty())
                    <h2 class="text-xl font-semibold mb-4">Day-by-Day Itinerary</h2>
                    <div class="space-y-4 mb-6">
                        @foreach($package->itineraryDays as $day)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-800">Day {{ $day->day_number }}: {{ $day->title }}</h3>
                                @if($day->description)
                                    <p class="text-sm text-gray-600 mt-1">{{ $day->description }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Inclusions / Exclusions --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    @if($package->inclusions)
                        <div>
                            <h2 class="text-lg font-semibold text-green-700 mb-2">✅ Inclusions</h2>
                            <ul class="text-sm text-gray-600 space-y-1">
                                @foreach($package->inclusions as $inc)
                                    <li>• {{ $inc }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if($package->exclusions)
                        <div>
                            <h2 class="text-lg font-semibold text-red-600 mb-2">❌ Exclusions</h2>
                            <ul class="text-sm text-gray-600 space-y-1">
                                @foreach($package->exclusions as $exc)
                                    <li>• {{ $exc }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                @if($package->terms)
                    <h2 class="text-xl font-semibold mb-2">Terms & Conditions</h2>
                    <p class="text-sm text-gray-500">{{ $package->terms }}</p>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white border border-gray-200 rounded-xl p-6 sticky top-20">
                    <div class="text-center mb-4">
                        <div class="text-sm text-gray-500">Starting from</div>
                        <div class="text-3xl font-bold text-[#0F4C81]">₹{{ number_format($package->price_from_inr->toRupees()) }}</div>
                        <div class="text-sm text-gray-400">per person</div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4 text-sm text-gray-600">
                        <div class="bg-gray-50 p-2 rounded text-center">
                            <div class="font-semibold">{{ $package->duration_days }}</div>
                            <div class="text-xs">Days</div>
                        </div>
                        <div class="bg-gray-50 p-2 rounded text-center">
                            <div class="font-semibold">{{ $package->duration_nights }}</div>
                            <div class="text-xs">Nights</div>
                        </div>
                    </div>

                    <a href="{{ route('contact') }}" class="btn-primary w-full text-center block mb-3">Enquire Now</a>
                    @auth('customer')
                        <a href="{{ route('customer.enquiries') }}" class="block text-center text-sm text-[#0F4C81] hover:underline">Submit Enquiry via Portal</a>
                    @else
                        <a href="{{ route('customer.register') }}" class="block text-center text-sm text-[#0F4C81] hover:underline">Sign up for personalized quotes</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

</x-public-layout>
