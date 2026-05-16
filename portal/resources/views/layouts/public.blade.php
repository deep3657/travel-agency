<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Maruti Travels' }} - Expert Travel Solutions</title>
    @if(isset($seoDescription))
        <meta name="description" content="{{ $seoDescription }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --accent: #0F4C81; }
        .btn-primary { background-color: var(--accent); color: white; padding: 0.6rem 1.5rem; border-radius: 0.375rem; font-weight: 600; transition: opacity 0.2s; }
        .btn-primary:hover { opacity: 0.85; }
    </style>
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    {{-- Navigation --}}
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-md bg-[#0F4C81] text-white font-semibold text-lg">M</span>
                    <span class="text-xl font-bold text-gray-900">Maruti Travels</span>
                </a>
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-sm font-medium {{ request()->routeIs('home') ? 'text-[#0F4C81]' : 'text-gray-600 hover:text-gray-900' }}">Home</a>
                    <a href="{{ route('packages.index') }}" class="text-sm font-medium {{ request()->routeIs('packages*') ? 'text-[#0F4C81]' : 'text-gray-600 hover:text-gray-900' }}">Packages</a>
                    <a href="{{ route('about') }}" class="text-sm font-medium {{ request()->routeIs('about') ? 'text-[#0F4C81]' : 'text-gray-600 hover:text-gray-900' }}">About</a>
                    <a href="{{ route('contact') }}" class="text-sm font-medium {{ request()->routeIs('contact') ? 'text-[#0F4C81]' : 'text-gray-600 hover:text-gray-900' }}">Contact</a>
                    @auth('customer')
                        <a href="{{ route('customer.account') }}" class="btn-primary text-sm">My Account</a>
                    @else
                        <a href="{{ route('customer.login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Login</a>
                        <a href="{{ route('customer.register') }}" class="btn-primary text-sm">Sign Up</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Main --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-300 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded bg-[#0F4C81] text-white font-semibold">M</span>
                        <span class="text-white font-bold">Maruti Travels</span>
                    </div>
                    <p class="text-sm text-gray-400">Your trusted partner for unforgettable travel experiences across India and beyond.</p>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-3">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('packages.index') }}" class="hover:text-white">Packages</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white">About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-3">Contact</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>📧 info@marutiravels.in</li>
                        <li>📞 +91 98765 43210</li>
                        <li>📍 Mumbai, Maharashtra</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-500">
                © {{ date('Y') }} Maruti Travels. All rights reserved.
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
