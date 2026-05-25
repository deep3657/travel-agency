<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Upload supplier document"
            :breadcrumbs="[
                ['label' => 'Supplier documents', 'href' => route('admin.supplier-docs.index')],
                ['label' => 'Upload'],
            ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />
            <livewire:admin.supplier-docs.supplier-doc-standalone />
        </div>
    </div>
</x-app-layout>
