<form wire:submit.prevent="save" class="space-y-6">
    @if (session('error'))
        <div class="mt-alert-error">
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Core identity --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="mt-label">Full name <span class="text-red-500">*</span></label>
            <input type="text" wire:model="name" class="mt-input" />
            @error('name') <p class="mt-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mt-label">Phone <span class="text-red-500">*</span></label>
            <input type="tel" wire:model="phone" placeholder="+91 9XXXXXXXXX" class="mt-input" />
            @error('phone') <p class="mt-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mt-label">Email <span class="text-red-500">*</span></label>
            <input type="email" wire:model="email" class="mt-input" />
            @error('email') <p class="mt-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mt-label">Alt phone</label>
            <input type="tel" wire:model="alt_phone" class="mt-input" />
            @error('alt_phone') <p class="mt-error">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Address --}}
    <fieldset class="border border-ink-200 rounded-lg p-4">
        <legend class="text-sm font-medium text-ink-600 px-1">Address</legend>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
            <div class="md:col-span-2">
                <label class="mt-label">Address line 1</label>
                <input type="text" wire:model="address_line1" class="mt-input" />
                @error('address_line1') <p class="mt-error">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="mt-label">Address line 2</label>
                <input type="text" wire:model="address_line2" class="mt-input" />
            </div>
            <div>
                <label class="mt-label">City</label>
                <input type="text" wire:model="city" class="mt-input" />
            </div>
            <div>
                <label class="mt-label">State</label>
                <input type="text" wire:model="state" class="mt-input" />
            </div>
            <div>
                <label class="mt-label">Pincode</label>
                <input type="text" wire:model="pincode" maxlength="6" class="mt-input" />
                @error('pincode') <p class="mt-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mt-label">Country</label>
                <input type="text" wire:model="country" class="mt-input" />
            </div>
        </div>
    </fieldset>

    {{-- Tax / Company --}}
    <fieldset class="border border-ink-200 rounded-lg p-4">
        <legend class="text-sm font-medium text-ink-600 px-1">Tax &amp; Company (optional)</legend>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
            <div>
                <label class="mt-label">GSTIN</label>
                <input type="text" wire:model="gstin" maxlength="15"
                       placeholder="27ABCDE1234F1Z5"
                       class="mt-input uppercase" />
                @error('gstin') <p class="mt-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mt-label">Company name <span class="text-ink-400 text-xs">(required if GSTIN provided)</span></label>
                <input type="text" wire:model="company_name" class="mt-input" />
                @error('company_name') <p class="mt-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mt-label">PAN</label>
                <input type="text" wire:model="pan" maxlength="10"
                       placeholder="ABCDE1234F"
                       class="mt-input uppercase" />
                @error('pan') <p class="mt-error">{{ $message }}</p> @enderror
            </div>
        </div>
    </fieldset>

    {{-- Personal dates --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="mt-label">Date of birth</label>
            <input type="date" wire:model="dob" class="mt-input" />
            @error('dob') <p class="mt-error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="mt-label">Anniversary</label>
            <input type="date" wire:model="anniversary" class="mt-input" />
            @error('anniversary') <p class="mt-error">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Notes --}}
    <div>
        <label class="mt-label">Internal notes</label>
        <textarea wire:model="notes" rows="3" class="mt-textarea"></textarea>
        @error('notes') <p class="mt-error">{{ $message }}</p> @enderror
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-between pt-4 border-t border-ink-200/70">
        <a href="{{ $isEdit && $customer ? route('admin.customers.show', $customer->ulid) : route('admin.customers.index') }}"
           class="mt-btn-ghost mt-btn-sm">
            Cancel
        </a>
        <button type="submit" class="mt-btn-primary">
            <span wire:loading.remove wire:target="save">{{ $isEdit ? 'Save changes' : 'Create customer' }}</span>
            <span wire:loading wire:target="save">Saving…</span>
        </button>
    </div>
</form>
