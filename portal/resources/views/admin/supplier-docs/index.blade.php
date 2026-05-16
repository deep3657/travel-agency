<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Supplier Documents</h2>
            <a href="{{ route('admin.supplier-docs.new') }}" class="inline-flex items-center px-4 py-2 bg-blue-700 text-white text-sm font-medium rounded-md hover:bg-blue-800">+ Upload Document</a>
        </div>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-5 py-4 border-b">
                <p class="text-sm text-gray-500">Use the upload button to add supplier documents for AI extraction or manual reference.</p>
            </div>
        </div>
    </div>
</x-app-layout>
