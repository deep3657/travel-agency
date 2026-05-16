<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Edit Booking — {{ $booking->booking_ref }}</h2></x-slot>
    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <livewire:admin.bookings.booking-form :bookingId="$booking->id" />
    </div>
</x-app-layout>
