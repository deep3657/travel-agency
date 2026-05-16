<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.vendors.index') }}" class="hover:text-gray-700">Vendors</a>
            <span>/</span>
            <span class="font-semibold text-xl text-gray-800">New vendor</span>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <livewire:admin.vendors.vendor-form />
            </div>
        </div>
    </div>
</x-app-layout>
