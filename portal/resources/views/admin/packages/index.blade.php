<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Packages"
            subtitle="Reusable itineraries you can sell."
            :breadcrumbs="[
                ['label' => 'Packages'],
            ]">
            @can('create', \App\Models\Package::class)
                <a href="{{ route('admin.packages.create') }}" class="mt-btn-primary mt-btn-sm">
                    + Add package
                </a>
            @endcan
        </x-page-header>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />
            <livewire:admin.packages.packages-index />
        </div>
    </div>
</x-app-layout>
