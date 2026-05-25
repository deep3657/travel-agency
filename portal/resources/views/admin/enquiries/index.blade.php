<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Enquiries"
            subtitle="Triage, qualify and convert customer interest."
            :breadcrumbs="[
                ['label' => 'Enquiries'],
            ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />
            <livewire:admin.enquiries.enquiries-index />
        </div>
    </div>
</x-app-layout>
