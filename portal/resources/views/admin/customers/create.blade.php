<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="New customer"
            :breadcrumbs="[
                ['label' => 'Customers', 'href' => route('admin.customers.index')],
                ['label' => 'New customer'],
            ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />
            <div class="mt-card mt-card-body">
                <livewire:admin.customers.customer-form />
            </div>
        </div>
    </div>
</x-app-layout>
