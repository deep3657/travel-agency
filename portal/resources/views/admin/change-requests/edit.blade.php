<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Change request"
            :subtitle="$changeRequest->booking->booking_ref"
            :breadcrumbs="[
                ['label' => 'Change requests', 'href' => route('admin.change-requests.index')],
                ['label' => $changeRequest->booking->booking_ref],
            ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />
            <livewire:admin.change-requests.change-request-form :ulid="$changeRequest->ulid" />
        </div>
    </div>
</x-app-layout>
