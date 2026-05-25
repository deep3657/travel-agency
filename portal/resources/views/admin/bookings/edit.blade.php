<x-app-layout>
    <x-slot name="header">
        <x-page-header
            :title="'Edit booking — ' . $booking->booking_ref"
            :breadcrumbs="[
                ['label' => 'Bookings', 'href' => route('admin.bookings.index')],
                ['label' => $booking->booking_ref, 'href' => route('admin.bookings.show', $booking->ulid)],
                ['label' => 'Edit'],
            ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />
            <livewire:admin.bookings.booking-form :bookingId="$booking->id" />
        </div>
    </div>
</x-app-layout>
