<x-app-layout>
    <x-slot name="header">
        <x-page-header
            :title="$trip->name"
            subtitle="Trip detail"
            :breadcrumbs="[
                ['label' => 'Trips', 'href' => route('admin.trips.index')],
                ['label' => $trip->name],
            ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />
            <livewire:admin.trips.trip-show :ulid="$trip->ulid" />
        </div>
    </div>
</x-app-layout>
