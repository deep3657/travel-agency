<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Maruti Travels') }} — My Account</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <nav class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-3 flex justify-between items-center">
            <a href="{{ route('customer.account') }}" class="text-blue-800 font-bold text-lg">Maruti Travels</a>
            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('customer.account') }}" class="text-gray-600 hover:text-blue-700">Dashboard</a>
                <a href="{{ route('customer.enquiries') }}" class="text-gray-600 hover:text-blue-700">My Enquiries</a>
                <a href="{{ route('customer.trips') }}" class="text-gray-600 hover:text-blue-700">My Trips</a>
                <a href="{{ route('customer.profile') }}" class="text-gray-600 hover:text-blue-700">Profile</a>
                <form method="POST" action="{{ route('customer.logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-red-500 hover:text-red-700">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        {{ $slot }}
    </main>
    @livewireScripts
</body>
</html>
