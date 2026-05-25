<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="New booking"
            :breadcrumbs="[
                ['label' => 'Bookings', 'href' => route('admin.bookings.index')],
                ['label' => 'New booking'],
            ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />
            <livewire:admin.bookings.booking-form :tripId="request()->integer('trip_id') ?: null" />
        </div>
    </div>
</x-app-layout>
