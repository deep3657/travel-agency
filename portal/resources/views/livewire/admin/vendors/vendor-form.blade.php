<form wire:submit.prevent="save" class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Vendor name <span class="text-red-500">*</span></label>
            <input type="text" wire:model="name"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500" />
            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Vendor code <span class="text-gray-400 text-xs">(unique short code)</span></label>
            <input type="text" wire:model="code" placeholder="e.g. AIR-IND"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase" />
            @error('code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Contact person</label>
            <input type="text" wire:model="contact_person"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500" />
            @error('contact_person') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" wire:model="email"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500" />
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Phone</label>
            <input type="tel" wire:model="phone"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500" />
            @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">GSTIN</label>
            <input type="text" wire:model="gstin" maxlength="15" placeholder="27ABCDE1234F1Z5"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase" />
            @error('gstin') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Payment terms (days)</label>
            <input type="number" wire:model="payment_terms_days" min="0" max="365"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500" />
            <p class="mt-1 text-xs text-gray-500">0 = immediate payment.</p>
            @error('payment_terms_days') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Address</label>
        <textarea wire:model="address" rows="2"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
        @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Internal notes</label>
        <textarea wire:model="notes" rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
        @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center justify-between">
        <a href="{{ $isEdit && $vendor ? route('admin.vendors.show', $vendor->ulid) : route('admin.vendors.index') }}"
           class="text-sm text-gray-500 hover:underline">Cancel</a>
        <button type="submit"
                class="inline-flex items-center rounded-md bg-[var(--mt-accent,#0F4C81)] px-4 py-2 text-sm font-medium text-white shadow-sm hover:opacity-90">
            <span wire:loading.remove wire:target="save">{{ $isEdit ? 'Save changes' : 'Create vendor' }}</span>
            <span wire:loading wire:target="save">Saving…</span>
        </button>
    </div>
</form>
