<div class="bg-white rounded-lg shadow-sm p-6">
    @if(session()->has('message'))
        <div class="mb-4 bg-green-50 border border-green-200 rounded-md p-3 text-sm text-green-700">{{ session('message') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 rounded-md p-3 text-sm text-red-700">
            @foreach($errors->all() as $err)<p>{{ $err }}</p>@endforeach
        </div>
    @endif

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input wire:model="name" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
            <input wire:model="phone" type="tel" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
            <textarea wire:model="address" rows="2" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
            <input wire:model="city" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
            <input wire:model="state" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button wire:click="save" class="px-5 py-2 bg-blue-700 text-white text-sm rounded-md hover:bg-blue-800">Save Changes</button>
    </div>
</div>
