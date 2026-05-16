<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Edit Package</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <livewire:admin.packages.package-form :ulid="$ulid" />
        </div>
    </div>
</x-app-layout>
