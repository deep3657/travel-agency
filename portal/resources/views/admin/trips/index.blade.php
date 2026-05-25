<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Trips"
            subtitle="Plan, quote and fulfil customer journeys."
            :breadcrumbs="[
                ['label' => 'Trips'],
            ]">
            <a href="{{ route('admin.trips.create') }}" class="mt-btn-primary mt-btn-sm">
                + New trip
            </a>
        </x-page-header>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />
            <livewire:admin.trips.trips-index />
        </div>
    </div>
</x-app-layout>
