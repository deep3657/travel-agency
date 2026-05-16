<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.customers.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">Customers</a>
            <span class="text-gray-400">/</span>
            <h2 class="font-semibold text-xl text-gray-800">New customer</h2>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <livewire:admin.customers.customer-form />
            </div>
        </div>
    </div>
</x-app-layout>
