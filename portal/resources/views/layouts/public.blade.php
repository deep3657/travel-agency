<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Maruti Travels' }} — Curated travel experiences across India & beyond</title>
    @if(isset($seoDescription))
        <meta name="description" content="{{ $seoDescription }}">
    @else
        <meta name="description" content="Maruti Travels — flights, hotels and curated holiday packages, planned by experts. Tailored quotations, transparent pricing and white-glove service.">
    @endif
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans">
    {{-- Top utility bar --}}
    <div class="hidden sm:block bg-brand-900 text-brand-100 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between py-1.5">
            <div class="flex items-center gap-5">
                <span class="inline-flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    info@marutitravels.in
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h2.28a2 2 0 011.95 1.55l.7 3.06a2 2 0 01-1.06 2.21l-1.43.71a11 11 0 005.06 5.06l.71-1.43a2 2 0 012.21-1.06l3.06.7A2 2 0 0121 16.72V19a2 2 0 01-2 2h-1C9.72 21 3 14.28 3 6V5z"/></svg>
                    +91 98765 43210
                </span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('contact') }}" class="hover:text-white">Need help?</a>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav x-data="{ open: false }" class="bg-white/95 backdrop-blur sticky top-0 z-50 border-b border-ink-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" class="flex items-center">
                    <x-brand-mark size="md" />
                </a>
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-sm font-medium {{ request()->routeIs('home') ? 'text-brand-700' : 'text-ink-600 hover:text-ink-900' }}">Home</a>
                    <a href="{{ route('packages.index') }}" class="text-sm font-medium {{ request()->routeIs('packages*') ? 'text-brand-700' : 'text-ink-600 hover:text-ink-900' }}">Packages</a>
                    <a href="{{ route('about') }}" class="text-sm font-medium {{ request()->routeIs('about') ? 'text-brand-700' : 'text-ink-600 hover:text-ink-900' }}">About</a>
                    <a href="{{ route('contact') }}" class="text-sm font-medium {{ request()->routeIs('contact') ? 'text-brand-700' : 'text-ink-600 hover:text-ink-900' }}">Contact</a>
                    @auth('customer')
                        <a href="{{ route('customer.account') }}" class="mt-btn-primary mt-btn-sm">My Account</a>
                    @else
                        <a href="{{ route('customer.login') }}" class="text-sm font-medium text-ink-600 hover:text-ink-900">Sign in</a>
                        <a href="{{ route('customer.register') }}" class="mt-btn-primary mt-btn-sm">Join us</a>
                    @endauth
                </div>
                <button @click="open = !open" class="md:hidden p-2 rounded-md text-ink-600 hover:bg-ink-100" aria-label="Toggle menu">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
            {{-- Mobile menu --}}
            <div x-show="open" x-transition class="md:hidden pb-3 space-y-1" x-cloak>
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-ink-700 hover:bg-ink-100">Home</a>
                <a href="{{ route('packages.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-ink-700 hover:bg-ink-100">Packages</a>
                <a href="{{ route('about') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-ink-700 hover:bg-ink-100">About</a>
                <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-ink-700 hover:bg-ink-100">Contact</a>
                @auth('customer')
                    <a href="{{ route('customer.account') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-brand-700 bg-brand-50">My Account</a>
                @else
                    <a href="{{ route('customer.login') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-ink-700 hover:bg-ink-100">Sign in</a>
                    <a href="{{ route('customer.register') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-white bg-brand-700">Join us</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Main --}}
    <main class="min-h-[60vh]">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-ink-900 text-ink-300 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <div class="md:col-span-2">
                    <a href="{{ route('home') }}" class="inline-block">
                        <span class="inline-flex items-center gap-2.5">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-brand-500 to-brand-700 text-white font-bold shadow-sm">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16v-2l-8-5V3.5a1.5 1.5 0 0 0-3 0V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>
                            </span>
                            <span class="font-display font-bold text-white text-lg">Maruti Travels</span>
                        </span>
                    </a>
                    <p class="text-sm text-ink-400 max-w-md mt-4">
                        Trusted travel partners since day one. We handle flights, hotels, transfers and bespoke holiday
                        packages — so you can focus on the journey.
                    </p>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wide">Explore</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('packages.index') }}" class="hover:text-white transition">Packages</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white transition">About us</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contact</a></li>
                        <li><a href="{{ route('customer.login') }}" class="hover:text-white transition">Customer login</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wide">Reach us</h3>
                    <ul class="space-y-2 text-sm text-ink-400">
                        <li class="inline-flex items-start gap-2">
                            <svg class="h-4 w-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            info@marutitravels.in
                        </li>
                        <li class="inline-flex items-start gap-2">
                            <svg class="h-4 w-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h2.28a2 2 0 011.95 1.55l.7 3.06a2 2 0 01-1.06 2.21l-1.43.71a11 11 0 005.06 5.06l.71-1.43a2 2 0 012.21-1.06l3.06.7A2 2 0 0121 16.72V19a2 2 0 01-2 2h-1C9.72 21 3 14.28 3 6V5z"/></svg>
                            +91 98765 43210
                        </li>
                        <li class="inline-flex items-start gap-2">
                            <svg class="h-4 w-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.66 16.66L13.41 20.9a2 2 0 01-2.83 0l-4.24-4.24a8 8 0 1111.31 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Mumbai, Maharashtra
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-ink-700 mt-10 pt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm text-ink-500">
                <span>© {{ date('Y') }} Maruti Travels. All rights reserved.</span>
                <span class="text-xs">Crafted with care for Indian and international travellers.</span>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
