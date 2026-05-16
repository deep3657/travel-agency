<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('admin.customers.index') }}" class="hover:text-gray-700">Customers</a>
                <span>/</span>
                <span class="font-semibold text-xl text-gray-800">{{ $customer->name }}</span>
            </div>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif
            <livewire:admin.customers.customer-show :ulid="$customer->ulid" />
        </div>
    </div>
</x-app-layout>
