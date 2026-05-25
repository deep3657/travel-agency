<x-public-layout title="Contact Us">
    <section class="bg-hero-gradient text-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 sm:py-16">
            <span class="text-xs font-semibold uppercase tracking-wider text-brand-200">Reach us</span>
            <h1 class="font-display text-4xl sm:text-5xl font-bold mt-2">Let's plan something memorable</h1>
            <p class="text-brand-100 mt-3 max-w-2xl">Tell us where you'd like to go and we'll get back with a tailored quotation within one business day.</p>
        </div>
    </section>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            {{-- Contact info --}}
            <div class="lg:col-span-2 space-y-4">
                @foreach ([
                    ['Email',   'info@marutitravels.in',                    'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    ['Phone',   '+91 98765 43210',                         'M3 5a2 2 0 012-2h2.28a2 2 0 011.95 1.55l.7 3.06a2 2 0 01-1.06 2.21l-1.43.71a11 11 0 005.06 5.06l.71-1.43a2 2 0 012.21-1.06l3.06.7A2 2 0 0121 16.72V19a2 2 0 01-2 2h-1C9.72 21 3 14.28 3 6V5z'],
                    ['Hours',   'Mon–Sat · 9 AM to 7 PM IST',              'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['Address', "123 Travel Street, Andheri West\nMumbai, Maharashtra 400053", 'M17.66 16.66L13.41 20.9a2 2 0 01-2.83 0l-4.24-4.24a8 8 0 1111.31 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                ] as [$label, $value, $iconPath])
                    <div class="mt-card mt-card-body">
                        <div class="flex gap-4">
                            <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $iconPath }}"/></svg>
                            </span>
                            <div>
                                <div class="text-xs uppercase tracking-wide font-semibold text-ink-500">{{ $label }}</div>
                                <div class="text-sm text-ink-800 mt-0.5 whitespace-pre-line">{{ $value }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Form --}}
            <div class="lg:col-span-3">
                <div class="mt-card mt-card-body p-6 sm:p-8">
                    @auth('customer')
                        {{-- Logged-in customer: nudge them to the portal form which doesn't ask for name/phone --}}
                        @php $customer = auth('customer')->user()?->customer; @endphp
                        <h2 class="font-display text-2xl font-bold text-ink-900">Hi {{ explode(' ', $customer?->name ?? '')[0] ?? 'there' }} — submit a detailed enquiry</h2>
                        <p class="text-sm text-ink-500 mt-1">
                            We already have your name, email and phone on file. Use the in-portal enquiry form to
                            tell us about your trip — we'll get back to you within a business day.
                        </p>
                        <div class="mt-6 flex flex-col gap-3">
                            <a href="{{ route('customer.enquiries') }}#enquiry_type" class="mt-btn-primary w-full">
                                Submit a new enquiry
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                            <a href="{{ route('customer.enquiries') }}" class="mt-btn-secondary w-full">
                                View my existing enquiries
                            </a>
                        </div>
                    @else
                        <h2 class="font-display text-2xl font-bold text-ink-900">Send us a message</h2>
                        <p class="text-sm text-ink-500 mt-1">
                            Or <a href="{{ route('customer.register') }}" class="text-brand-700 hover:underline font-medium">create an account</a>
                            to submit a detailed enquiry and track its progress.
                        </p>

                        @if (session('status'))
                            <div class="mt-alert-success mt-6" role="status">
                                <svg class="h-5 w-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ session('status') }}</span>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mt-alert-error mt-6" role="alert">
                                <svg class="h-5 w-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/></svg>
                                <div>
                                    <p class="font-medium">Please correct the following:</p>
                                    <ul class="mt-1 list-disc list-inside text-xs">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('contact.store') }}" class="mt-6 space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="contact_name" class="mt-label">Your name</label>
                                    <input id="contact_name" type="text" name="name" required maxlength="120"
                                           value="{{ old('name') }}" placeholder="e.g. Rohan Sharma" class="mt-input">
                                </div>
                                <div>
                                    <label for="contact_email" class="mt-label">Email</label>
                                    <input id="contact_email" type="email" name="email" required maxlength="190"
                                           value="{{ old('email') }}" placeholder="you@example.com" class="mt-input">
                                </div>
                            </div>
                            <div>
                                <label for="contact_phone" class="mt-label">Phone</label>
                                <input id="contact_phone" type="tel" name="phone" required maxlength="20"
                                       value="{{ old('phone') }}" placeholder="+91 98765 43210" class="mt-input">
                            </div>
                            <div>
                                <label for="contact_message" class="mt-label">Tell us about your trip</label>
                                <textarea id="contact_message" name="message" rows="5" required maxlength="5000"
                                          placeholder="Destinations, dates, traveller count, budget, anything special…"
                                          class="mt-textarea">{{ old('message') }}</textarea>
                            </div>
                            <button type="submit" class="mt-btn-primary w-full">
                                Send message
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                            <p class="text-xs text-ink-500 text-center">We typically reply within one business day.</p>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
