<x-app-layout>
    <x-slot name="header">
        <x-page-header
            :title="$customer->name"
            :breadcrumbs="[
                ['label' => 'Customers', 'href' => route('admin.customers.index')],
                ['label' => $customer->name],
            ]">
            @can('update', $customer)
                <a href="{{ route('admin.customers.edit', $customer->ulid) }}" class="mt-btn-primary mt-btn-sm">
                    Edit customer
                </a>
            @endcan
        </x-page-header>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />
            <livewire:admin.customers.customer-show :ulid="$customer->ulid" />
        </div>
    </div>
</x-app-layout>
