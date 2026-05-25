<x-app-layout>
    <x-slot name="header">
        <x-page-header
            :title="$enquiry->customer->name"
            subtitle="Enquiry detail"
            :breadcrumbs="[
                ['label' => 'Enquiries', 'href' => route('admin.enquiries.index')],
                ['label' => $enquiry->customer->name],
            ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />
            <livewire:admin.enquiries.enquiry-show :ulid="$enquiry->ulid" />
        </div>
    </div>
</x-app-layout>
