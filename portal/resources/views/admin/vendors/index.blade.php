<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Vendors</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif
            <livewire:admin.vendors.vendors-index />
        </div>
    </div>
</x-app-layout>
