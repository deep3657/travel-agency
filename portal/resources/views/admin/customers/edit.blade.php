<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Edit customer"
            :breadcrumbs="[
                ['label' => 'Customers', 'href' => route('admin.customers.index')],
                ['label' => $ulid, 'href' => route('admin.customers.show', $ulid)],
                ['label' => 'Edit'],
            ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />
            <div class="mt-card mt-card-body">
                <livewire:admin.customers.customer-form :ulid="$ulid" />
            </div>
        </div>
    </div>
</x-app-layout>
