<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.enquiries.index') }}" class="text-gray-400 hover:text-gray-600">← Enquiries</a>
            <h2 class="font-semibold text-xl text-gray-800">Enquiry Detail</h2>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <livewire:admin.enquiries.enquiry-show :ulid="$enquiry->ulid" />
        </div>
    </div>
</x-app-layout>
