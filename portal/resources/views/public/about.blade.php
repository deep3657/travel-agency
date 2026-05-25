<x-public-layout title="About Us">
    {{-- Hero --}}
    <section class="bg-hero-gradient text-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
            <span class="text-xs font-semibold uppercase tracking-wider text-brand-200">About</span>
            <h1 class="font-display text-4xl sm:text-5xl font-bold mt-2">Crafting journeys since 2005</h1>
            <p class="text-brand-100 mt-4 text-lg max-w-3xl">
                Maruti Travels is a full-service travel agency specialising in personalised holiday packages,
                flights, hotels and end-to-end travel management — across India and around the world.
            </p>
        </div>
    </section>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="mt-card mt-card-body text-center">
                <div class="font-display text-4xl font-bold text-brand-700">5,000+</div>
                <div class="text-ink-600 mt-1 text-sm">Happy travellers</div>
            </div>
            <div class="mt-card mt-card-body text-center">
                <div class="font-display text-4xl font-bold text-brand-700">200+</div>
                <div class="text-ink-600 mt-1 text-sm">Destinations covered</div>
            </div>
            <div class="mt-card mt-card-body text-center">
                <div class="font-display text-4xl font-bold text-brand-700">19+</div>
                <div class="text-ink-600 mt-1 text-sm">Years of experience</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
            <div>
                <h2 class="font-display text-2xl font-bold text-ink-900">Our story</h2>
                <div class="text-ink-600 space-y-4 mt-4 leading-relaxed">
                    <p>What began as a small family-run travel desk has grown into a trusted destination management partner for thousands of Indian and international travellers.</p>
                    <p>Our team of dedicated travel experts is committed to crafting personalised itineraries that match your budget, preferences and travel style — from honeymoons to corporate offsites, from weekend getaways to multi-country adventures.</p>
                    <p>We pride ourselves on transparent pricing, exceptional customer service and end-to-end travel support — from the moment you enquire to the time you return home.</p>
                </div>
            </div>
            <div class="space-y-4">
                @foreach ([
                    ['Personalised planning', 'No cookie-cutter trips. Every itinerary is tailored to you.'],
                    ['GST-compliant invoicing', 'Transparent pricing with proper tax invoices for businesses too.'],
                    ['Pan-India presence', 'Strong supplier network across 200+ destinations.'],
                    ['Round-the-clock support', '24×7 reachable on-trip — we never leave you stranded.'],
                ] as [$t, $d])
                    <div class="flex gap-4 mt-card mt-card-body">
                        <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <div>
                            <h3 class="font-semibold text-ink-900">{{ $t }}</h3>
                            <p class="text-sm text-ink-600 mt-0.5">{{ $d }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-public-layout>
