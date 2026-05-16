<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.trips.index') }}" class="text-gray-400 hover:text-gray-600">← Trips</a>
            <h2 class="font-semibold text-xl text-gray-800">Trip Detail</h2>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <livewire:admin.trips.trip-show :ulid="$trip->ulid" />
        </div>
    </div>
</x-app-layout>
