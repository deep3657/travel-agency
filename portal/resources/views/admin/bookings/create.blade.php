<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">New Booking</h2></x-slot>
    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <livewire:admin.bookings.booking-form :tripId="request()->integer('trip_id') ?: null" />
    </div>
</x-app-layout>
