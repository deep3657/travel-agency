<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $pageTitle ?? 'Admin' }} · {{ config('app.name', 'Maruti Travels') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-ink-50 relative">
            {{-- Ambient brand gradient backdrop --}}
            <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-0 h-[420px] bg-gradient-to-b from-brand-50/70 via-brand-50/20 to-transparent"></div>

            <div class="relative">
                @include('layouts.navigation')

                @isset($header)
                    <header class="bg-white/70 backdrop-blur-sm border-b border-ink-200/70">
                        <div class="max-w-[1400px] mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
        @livewireScripts
    </body>
</html>
