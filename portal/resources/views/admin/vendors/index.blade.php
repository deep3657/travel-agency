<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Vendors"
            subtitle="Suppliers powering your bookings."
            :breadcrumbs="[
                ['label' => 'Vendors'],
            ]">
            @can('create', \App\Models\Vendor::class)
                <a href="{{ route('admin.vendors.create') }}" class="mt-btn-primary mt-btn-sm">
                    + Add vendor
                </a>
            @endcan
        </x-page-header>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />
            <livewire:admin.vendors.vendors-index />
        </div>
    </div>
</x-app-layout>
