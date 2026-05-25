<div class="mt-card mt-card-body">
    <form wire:submit="save" class="space-y-5">
        @if($errors->any())
            <div class="mt-alert-error">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="mt-label">Title *</label>
                <input wire:model.live="title" type="text" class="mt-input">
                @error('title')<span class="mt-error">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="mt-label">Slug *</label>
                <input wire:model="slug" type="text" class="mt-input">
                @error('slug')<span class="mt-error">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="mt-label">Destinations *</label>
                <input wire:model="destinations" type="text" placeholder="e.g. Delhi, Agra, Jaipur" class="mt-input">
                @error('destinations')<span class="mt-error">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="mt-label">Departure city</label>
                <input wire:model="departure_city" type="text" class="mt-input">
            </div>

            <div>
                <label class="mt-label">Price from (₹) *</label>
                <input wire:model="price_from_inr" type="number" step="0.01" min="0" class="mt-input">
                @error('price_from_inr')<span class="mt-error">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="mt-label">Duration days *</label>
                <input wire:model="duration_days" type="number" min="1" class="mt-input">
            </div>

            <div>
                <label class="mt-label">Duration nights *</label>
                <input wire:model="duration_nights" type="number" min="0" class="mt-input">
            </div>

            <div>
                <label class="mt-label">Status</label>
                <select wire:model="status" class="mt-select">
                    <option value="draft">Draft</option>
                    <option value="active">Active</option>
                </select>
            </div>

            <div>
                <label class="mt-label">Hero image URL</label>
                <input wire:model="hero_image_path" type="text" class="mt-input">
            </div>

            <div class="md:col-span-2">
                <label class="mt-label">Short description</label>
                <textarea wire:model="short_description" rows="2" class="mt-textarea"></textarea>
            </div>

            <div class="md:col-span-2">
                <label class="mt-label">Long description</label>
                <textarea wire:model="long_description" rows="5" class="mt-textarea"></textarea>
            </div>

            <div>
                <label class="mt-label">Inclusions (one per line)</label>
                <textarea wire:model="inclusions_text" rows="4" placeholder="Accommodation&#10;Breakfast&#10;Airport transfers" class="mt-textarea"></textarea>
            </div>

            <div>
                <label class="mt-label">Exclusions (one per line)</label>
                <textarea wire:model="exclusions_text" rows="4" placeholder="Flights&#10;Personal expenses" class="mt-textarea"></textarea>
            </div>

            <div class="md:col-span-2">
                <label class="mt-label">Terms &amp; conditions</label>
                <textarea wire:model="terms" rows="4" class="mt-textarea"></textarea>
            </div>

            <div>
                <label class="mt-label">SEO meta title</label>
                <input wire:model="seo_meta_title" type="text" class="mt-input">
            </div>

            <div>
                <label class="mt-label">SEO meta description</label>
                <input wire:model="seo_meta_description" type="text" class="mt-input">
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-ink-200/70">
            <a href="{{ route('admin.packages.index') }}" class="mt-btn-secondary mt-btn-sm">Cancel</a>
            <button type="submit" class="mt-btn-primary">
                {{ $isEdit ? 'Save changes' : 'Create package' }}
            </button>
        </div>
    </form>
</div>
