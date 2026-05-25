<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Supplier documents"
            subtitle="Uploads pending AI extraction & review."
            :breadcrumbs="[
                ['label' => 'Supplier documents'],
            ]">
            <a href="{{ route('admin.supplier-docs.new') }}" class="mt-btn-primary mt-btn-sm">
                + Upload document
            </a>
        </x-page-header>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />

            @livewire(\App\Livewire\Admin\SupplierDocs\SupplierDocsIndex::class)
        </div>
    </div>
</x-app-layout>
