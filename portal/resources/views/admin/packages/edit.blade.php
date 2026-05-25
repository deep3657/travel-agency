<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Edit package"
            :breadcrumbs="[
                ['label' => 'Packages', 'href' => route('admin.packages.index')],
                ['label' => $ulid, 'href' => route('admin.packages.show', $ulid)],
                ['label' => 'Edit'],
            ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />
            <livewire:admin.packages.package-form :ulid="$ulid" />
        </div>
    </div>
</x-app-layout>
