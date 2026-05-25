<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Customers"
            subtitle="Manage your customer master records."
            :breadcrumbs="[
                ['label' => 'Customers'],
            ]">
            @can('create', \App\Models\Customer::class)
                <a href="{{ route('admin.customers.create') }}" class="mt-btn-primary mt-btn-sm">
                    + Add customer
                </a>
            @endcan
        </x-page-header>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />
            <livewire:admin.customers.customers-index />
        </div>
    </div>
</x-app-layout>
