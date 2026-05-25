<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="New package"
            :breadcrumbs="[
                ['label' => 'Packages', 'href' => route('admin.packages.index')],
                ['label' => 'New package'],
            ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />
            <livewire:admin.packages.package-form />
        </div>
    </div>
</x-app-layout>
