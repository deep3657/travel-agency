<x-public-layout :title="$package->seo_meta_title ?? $package->title" :seoDescription="$package->seo_meta_description ?? $package->short_description">
    {{-- Hero --}}
    <section class="relative">
        @if($package->hero_image_path)
            <div class="h-72 sm:h-96 bg-cover bg-center" style="background-image: url('{{ $package->hero_image_path }}')"></div>
            <div class="absolute inset-0 bg-hero-overlay"></div>
        @else
            <div class="h-72 sm:h-96 bg-hero-gradient"></div>
        @endif
        <div class="absolute inset-0 flex items-end">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 w-full">
                <nav class="text-xs text-white/80 mb-3">
                    <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                    <span class="mx-1">/</span>
                    <a href="{{ route('packages.index') }}" class="hover:text-white">Packages</a>
                    <span class="mx-1">/</span>
                    <span class="text-white">{{ $package->title }}</span>
                </nav>
                <h1 class="font-display text-3xl sm:text-4xl font-bold text-white">{{ $package->title }}</h1>
                <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-brand-100">
                    <span class="inline-flex items-center gap-1">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.66 16.66L13.41 20.9a2 2 0 01-2.83 0l-4.24-4.24a8 8 0 1111.31 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $package->destinations }}
                    </span>
                    @if($package->departure_city)
                        <span class="text-white/60">·</span>
                        <span>Departs from {{ $package->departure_city }}</span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                @if($package->category_tags && count($package->category_tags))
                    <div class="flex flex-wrap gap-2">
                        @foreach($package->category_tags as $tag)
                            <span class="mt-pill-blue">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

                @if($package->short_description)
                    <p class="text-lg text-ink-700 leading-relaxed">{{ $package->short_description }}</p>
                @endif

                @if($package->highlights)
                    <section class="mt-card mt-card-body">
                        <h2 class="font-display text-xl font-bold text-ink-900 mb-3">Highlights</h2>
                        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-ink-700">
                            @foreach($package->highlights as $h)
                                <li class="flex items-start gap-2">
                                    <svg class="h-4 w-4 mt-0.5 text-brand-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>{{ $h }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if($package->long_description)
                    <section>
                        <h2 class="font-display text-xl font-bold text-ink-900 mb-3">About this package</h2>
                        <p class="text-ink-700 leading-relaxed whitespace-pre-line">{{ $package->long_description }}</p>
                    </section>
                @endif

                @if($package->itineraryDays->isNotEmpty())
                    <section>
                        <h2 class="font-display text-xl font-bold text-ink-900 mb-4">Day-by-day itinerary</h2>
                        <ol class="relative border-l-2 border-brand-100 ml-3 space-y-6">
                            @foreach($package->itineraryDays as $day)
                                <li class="ml-6">
                                    <span class="absolute -left-[13px] flex h-6 w-6 items-center justify-center rounded-full bg-brand-700 text-white text-xs font-bold">{{ $day->day_number }}</span>
                                    <div class="mt-card mt-card-body">
                                        <h3 class="font-semibold text-ink-900">{{ $day->title }}</h3>
                                        @if($day->description)
                                            <p class="text-sm text-ink-600 mt-1.5 whitespace-pre-line">{{ $day->description }}</p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </section>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($package->inclusions)
                        <section class="mt-card mt-card-body">
                            <h2 class="font-semibold text-emerald-700 mb-3 inline-flex items-center gap-2">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                Inclusions
                            </h2>
                            <ul class="text-sm text-ink-700 space-y-1.5">
                                @foreach($package->inclusions as $inc)
                                    <li class="flex items-start gap-2"><span class="text-emerald-600 mt-0.5">✓</span><span>{{ $inc }}</span></li>
                                @endforeach
                            </ul>
                        </section>
                    @endif
                    @if($package->exclusions)
                        <section class="mt-card mt-card-body">
                            <h2 class="font-semibold text-rose-700 mb-3 inline-flex items-center gap-2">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Exclusions
                            </h2>
                            <ul class="text-sm text-ink-700 space-y-1.5">
                                @foreach($package->exclusions as $exc)
                                    <li class="flex items-start gap-2"><span class="text-rose-600 mt-0.5">×</span><span>{{ $exc }}</span></li>
                                @endforeach
                            </ul>
                        </section>
                    @endif
                </div>

                @if($package->terms)
                    <section class="mt-card mt-card-body">
                        <h2 class="font-semibold text-ink-800 mb-2">Terms &amp; conditions</h2>
                        <p class="text-sm text-ink-600 whitespace-pre-line">{{ $package->terms }}</p>
                    </section>
                @endif
            </div>

            {{-- Sidebar: pricing card --}}
            <aside class="lg:col-span-1">
                <div class="mt-card mt-card-body sticky top-24">
                    <div class="text-center pb-4 border-b border-ink-100">
                        <div class="text-xs uppercase tracking-wide text-ink-500">Starting from</div>
                        <div class="font-display text-3xl font-bold text-ink-900 mt-1">₹{{ number_format($package->price_from_inr->toRupees()) }}</div>
                        <div class="text-xs text-ink-500">per person · GST extra</div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 my-4">
                        <div class="bg-ink-50 rounded-lg p-3 text-center">
                            <div class="font-display text-2xl font-bold text-ink-900">{{ $package->duration_days }}</div>
                            <div class="text-xs uppercase tracking-wide text-ink-500 mt-0.5">Days</div>
                        </div>
                        <div class="bg-ink-50 rounded-lg p-3 text-center">
                            <div class="font-display text-2xl font-bold text-ink-900">{{ $package->duration_nights }}</div>
                            <div class="text-xs uppercase tracking-wide text-ink-500 mt-0.5">Nights</div>
                        </div>
                    </div>

                    @auth('customer')
                        <a href="{{ route('customer.enquiries') }}#enquiry_type" class="mt-btn-primary w-full">
                            Enquire now
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        <p class="text-center text-xs text-ink-500 mt-3">Signed in — we already have your contact details.</p>
                    @else
                        <a href="{{ route('contact') }}" class="mt-btn-primary w-full">
                            Enquire now
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        <a href="{{ route('customer.register') }}" class="block text-center text-sm text-brand-700 hover:text-brand-800 mt-3 font-medium">
                            New here? Sign up for personalised quotes
                        </a>
                    @endauth

                    <ul class="mt-5 space-y-2 text-xs text-ink-600">
                        <li class="flex items-center gap-2"><svg class="h-3.5 w-3.5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Transparent quotation, no hidden fees</li>
                        <li class="flex items-center gap-2"><svg class="h-3.5 w-3.5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>GST-compliant invoicing</li>
                        <li class="flex items-center gap-2"><svg class="h-3.5 w-3.5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>24×7 on-trip support</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</x-public-layout>
