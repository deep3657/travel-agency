<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Sign in · {{ config('app.name', 'Maruti Travels') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen grid lg:grid-cols-2 bg-ink-50">
            {{-- Left: Branding panel --}}
            <div class="hidden lg:flex relative bg-hero-gradient text-white p-10 flex-col justify-between overflow-hidden">
                <div class="absolute inset-0 opacity-15 pointer-events-none" aria-hidden="true">
                    <svg class="w-full h-full" viewBox="0 0 800 600" preserveAspectRatio="none">
                        <circle cx="120" cy="120" r="180" fill="white"/>
                        <circle cx="650" cy="500" r="220" fill="white"/>
                    </svg>
                </div>
                <div class="relative z-10">
                    <a href="/">
                        <span class="inline-flex items-center gap-2.5">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white/15 backdrop-blur text-white font-bold ring-1 ring-white/30">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16v-2l-8-5V3.5a1.5 1.5 0 0 0-3 0V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>
                            </span>
                            <span class="font-display font-bold text-lg">Maruti Travels</span>
                        </span>
                    </a>
                </div>
                <div class="relative z-10 max-w-md space-y-5">
                    <h1 class="font-display text-4xl xl:text-5xl font-bold leading-tight">
                        Plan brilliantly. Travel beautifully.
                    </h1>
                    <p class="text-brand-100 text-lg">
                        The internal hub for Maruti Travels — flights, hotels, packages, quotations, vouchers and more,
                        all in one tidy workspace.
                    </p>
                    <div class="flex items-center gap-4 pt-4">
                        <div class="flex -space-x-2">
                            <span class="h-8 w-8 rounded-full bg-white/20 ring-2 ring-white/40"></span>
                            <span class="h-8 w-8 rounded-full bg-white/20 ring-2 ring-white/40"></span>
                            <span class="h-8 w-8 rounded-full bg-white/20 ring-2 ring-white/40"></span>
                        </div>
                        <span class="text-sm text-brand-100">Trusted by our team since day one</span>
                    </div>
                </div>
                <div class="relative z-10 text-xs text-brand-200">
                    © {{ date('Y') }} Maruti Travels — Internal staff portal
                </div>
            </div>

            {{-- Right: Form --}}
            <div class="flex items-center justify-center px-4 sm:px-8 py-12">
                <div class="w-full max-w-md">
                    <div class="lg:hidden mb-8 flex justify-center">
                        <x-brand-mark size="lg" />
                    </div>
                    <div class="bg-white rounded-2xl shadow-card border border-ink-200/70 p-8">
                        {{ $slot }}
                    </div>
                    <p class="text-center text-xs text-ink-500 mt-6">
                        Customers — go to <a href="{{ route('customer.login') }}" class="text-brand-700 hover:underline font-medium">customer login</a>.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
