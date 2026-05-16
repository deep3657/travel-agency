<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.change-requests.index') }}" class="text-gray-400 hover:text-gray-600">← Change Requests</a>
            <h2 class="font-semibold text-xl text-gray-800">Change Request</h2>
        </div>
    </x-slot>
    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <livewire:admin.change-requests.change-request-form :ulid="$changeRequest->ulid" />
    </div>
</x-app-layout>
