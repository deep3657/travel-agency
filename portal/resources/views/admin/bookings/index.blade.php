<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Bookings"
            subtitle="Flights, hotels and packages confirmed with vendors."
            :breadcrumbs="[
                ['label' => 'Bookings'],
            ]">
            <a href="{{ route('admin.bookings.create') }}" class="mt-btn-primary mt-btn-sm">
                + New booking
            </a>
        </x-page-header>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />
            <livewire:admin.bookings.bookings-index />
        </div>
    </div>
</x-app-layout>
