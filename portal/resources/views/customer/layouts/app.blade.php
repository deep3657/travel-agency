<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'My Account' }} · {{ config('app.name', 'Maruti Travels') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-ink-50">
    @php
        $customer = auth('customer')->user();
        $custNav = [
            ['Overview',     'customer.account',   'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['My Enquiries', 'customer.enquiries', 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
            ['My Trips',     'customer.trips',     'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'],
            ['Profile',      'customer.profile',   'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ];
    @endphp
    <nav x-data="{ open: false }" class="bg-white border-b border-ink-200 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('customer.account') }}" class="flex items-center">
                    <x-brand-mark size="md" />
                </a>

                <div class="hidden md:flex md:items-center md:gap-1">
                    @foreach($custNav as [$label, $r, $d])
                        @php $active = request()->routeIs($r); @endphp
                        <a href="{{ route($r) }}"
                           class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ $active ? 'bg-brand-50 text-brand-700' : 'text-ink-600 hover:text-ink-900 hover:bg-ink-100' }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $d }}"/></svg>
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                <div class="hidden md:flex md:items-center md:gap-3">
                    <span class="text-sm text-ink-600">Hello, <span class="font-semibold text-ink-800">{{ $customer?->name }}</span></span>
                    <form method="POST" action="{{ route('customer.logout') }}">
                        @csrf
                        <button type="submit" class="mt-btn-ghost mt-btn-sm">Sign out</button>
                    </form>
                </div>

                <button @click="open = !open" class="md:hidden p-2 rounded-md text-ink-500 hover:bg-ink-100" aria-label="Toggle menu">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path :class="open ? 'hidden' : ''" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/><path :class="!open ? 'hidden' : ''" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div x-show="open" x-cloak x-transition class="md:hidden pb-3 space-y-1">
                @foreach($custNav as [$label, $r, $d])
                    <a href="{{ route($r) }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs($r) ? 'bg-brand-50 text-brand-700' : 'text-ink-700 hover:bg-ink-100' }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $d }}"/></svg>
                        {{ $label }}
                    </a>
                @endforeach
                <form method="POST" action="{{ route('customer.logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm text-rose-600 hover:bg-rose-50">Sign out</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <x-flash />
        {{ $slot }}
    </main>

    <footer class="border-t border-ink-200 mt-16 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-sm text-ink-500">
            <span>© {{ date('Y') }} Maruti Travels</span>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="hover:text-ink-700">Public site</a>
                <a href="{{ route('contact') }}" class="hover:text-ink-700">Contact</a>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
