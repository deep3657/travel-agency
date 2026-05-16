<div class="bg-white shadow-sm rounded-lg p-6">
    <form wire:submit="save" class="space-y-5">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-md p-3 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                <input wire:model.live="title" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('title')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug *</label>
                <input wire:model="slug" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('slug')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Destinations *</label>
                <input wire:model="destinations" type="text" placeholder="e.g. Delhi, Agra, Jaipur" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('destinations')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Departure City</label>
                <input wire:model="departure_city" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Price From (₹) *</label>
                <input wire:model="price_from_inr" type="number" step="0.01" min="0" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('price_from_inr')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Duration Days *</label>
                <input wire:model="duration_days" type="number" min="1" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Duration Nights *</label>
                <input wire:model="duration_nights" type="number" min="0" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="draft">Draft</option>
                    <option value="active">Active</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hero Image URL</label>
                <input wire:model="hero_image_path" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                <textarea wire:model="short_description" rows="2" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"></textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Long Description</label>
                <textarea wire:model="long_description" rows="5" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Inclusions (one per line)</label>
                <textarea wire:model="inclusions_text" rows="4" placeholder="Accommodation&#10;Breakfast&#10;Airport transfers" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Exclusions (one per line)</label>
                <textarea wire:model="exclusions_text" rows="4" placeholder="Flights&#10;Personal expenses" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"></textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Terms & Conditions</label>
                <textarea wire:model="terms" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">SEO Meta Title</label>
                <input wire:model="seo_meta_title" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">SEO Meta Description</label>
                <input wire:model="seo_meta_description" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="{{ route('admin.packages.index') }}" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</a>
            <button type="submit" class="px-5 py-2 bg-blue-700 text-white text-sm rounded-md hover:bg-blue-800">
                {{ $isEdit ? 'Save Changes' : 'Create Package' }}
            </button>
        </div>
    </form>
</div>
