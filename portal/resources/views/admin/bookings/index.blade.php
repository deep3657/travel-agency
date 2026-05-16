<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Bookings</h2>
            <a href="{{ route('admin.bookings.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-700 text-white text-sm font-medium rounded-md hover:bg-blue-800">+ New Booking</a>
        </div>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('status'))<div class="mb-4 rounded-md bg-green-50 border border-green-200 p-3 text-sm text-green-800">{{ session('status') }}</div>@endif
        <livewire:admin.bookings.bookings-index />
    </div>
</x-app-layout>
