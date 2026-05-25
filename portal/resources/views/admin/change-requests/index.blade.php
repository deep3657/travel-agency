<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Change requests"
            subtitle="Cancellations and modifications from customers."
            :breadcrumbs="[
                ['label' => 'Change requests'],
            ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />
            <livewire:admin.change-requests.change-requests-index />
        </div>
    </div>
</x-app-layout>
