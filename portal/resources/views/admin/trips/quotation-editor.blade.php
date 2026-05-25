<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Quotation editor"
            :subtitle="'Trip: '.$quotation->trip->name.' · '.$quotation->trip->customer->name"
            :breadcrumbs="[
                ['label' => 'Trips', 'href' => route('admin.trips.index')],
                ['label' => $quotation->trip->name, 'href' => route('admin.trips.show', $quotation->trip->ulid)],
                ['label' => 'Quotation editor'],
            ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />
            <livewire:admin.trips.quotation-editor :ulid="$quotation->ulid" />
        </div>
    </div>
</x-app-layout>
