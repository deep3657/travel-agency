<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.customers.index') }}" class="hover:text-gray-700">Customers</a>
            <span>/</span>
            <a href="{{ route('admin.customers.show', $ulid) }}" class="hover:text-gray-700">{{ $ulid }}</a>
            <span>/</span>
            <span class="font-semibold text-xl text-gray-800">Edit</span>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <livewire:admin.customers.customer-form :ulid="$ulid" />
            </div>
        </div>
    </div>
</x-app-layout>
