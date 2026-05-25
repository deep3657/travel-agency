<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Agency settings"
            subtitle="Agency profile, branding, AI provider & business rules."
            :breadcrumbs="[
                ['label' => 'Settings'],
            ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />
            <div class="mt-card mt-card-body">
                <livewire:admin.settings-form />
            </div>
        </div>
    </div>
</x-app-layout>
