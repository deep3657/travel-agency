<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="New vendor"
            :breadcrumbs="[
                ['label' => 'Vendors', 'href' => route('admin.vendors.index')],
                ['label' => 'New vendor'],
            ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />
            <div class="mt-card mt-card-body">
                <livewire:admin.vendors.vendor-form />
            </div>
        </div>
    </div>
</x-app-layout>
