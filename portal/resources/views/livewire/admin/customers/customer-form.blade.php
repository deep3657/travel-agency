<form wire:submit.prevent="save" class="space-y-6">
    @if (session('error'))
        <div class="rounded-md bg-red-50 border border-red-200 p-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    {{-- Core identity --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium text-gray-700">Full name <span class="text-red-500">*</span></label>
            <input type="text" wire:model="name"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500" />
            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Phone <span class="text-red-500">*</span></label>
            <input type="tel" wire:model="phone" placeholder="+91 9XXXXXXXXX"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500" />
            @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
            <input type="email" wire:model="email"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500" />
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Alt phone</label>
            <input type="tel" wire:model="alt_phone"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500" />
            @error('alt_phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Address --}}
    <fieldset class="border border-gray-200 rounded-md p-4">
        <legend class="text-sm font-medium text-gray-600 px-1">Address</legend>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Address line 1</label>
                <input type="text" wire:model="address_line1"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                @error('address_line1') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Address line 2</label>
                <input type="text" wire:model="address_line2"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">City</label>
                <input type="text" wire:model="city"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">State</label>
                <input type="text" wire:model="state"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Pincode</label>
                <input type="text" wire:model="pincode" maxlength="6"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                @error('pincode') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Country</label>
                <input type="text" wire:model="country"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500" />
            </div>
        </div>
    </fieldset>

    {{-- Tax / Company --}}
    <fieldset class="border border-gray-200 rounded-md p-4">
        <legend class="text-sm font-medium text-gray-600 px-1">Tax & Company (optional)</legend>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
            <div>
                <label class="block text-sm font-medium text-gray-700">GSTIN</label>
                <input type="text" wire:model="gstin" maxlength="15"
                       placeholder="27ABCDE1234F1Z5"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase" />
                @error('gstin') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Company name <span class="text-gray-400 text-xs">(required if GSTIN provided)</span></label>
                <input type="text" wire:model="company_name"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                @error('company_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">PAN</label>
                <input type="text" wire:model="pan" maxlength="10"
                       placeholder="ABCDE1234F"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase" />
                @error('pan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </fieldset>

    {{-- Personal dates --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium text-gray-700">Date of birth</label>
            <input type="date" wire:model="dob"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500" />
            @error('dob') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Anniversary</label>
            <input type="date" wire:model="anniversary"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500" />
            @error('anniversary') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Notes --}}
    <div>
        <label class="block text-sm font-medium text-gray-700">Internal notes</label>
        <textarea wire:model="notes" rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
        @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ $isEdit && $customer ? route('admin.customers.show', $customer->ulid) : route('admin.customers.index') }}"
           class="text-sm text-gray-500 hover:underline">
            Cancel
        </a>
        <button type="submit"
                class="inline-flex items-center rounded-md bg-[var(--mt-accent,#0F4C81)] px-4 py-2 text-sm font-medium text-white shadow-sm hover:opacity-90 focus:outline-none">
            <span wire:loading.remove wire:target="save">{{ $isEdit ? 'Save changes' : 'Create customer' }}</span>
            <span wire:loading wire:target="save">Saving…</span>
        </button>
    </div>
</form>
