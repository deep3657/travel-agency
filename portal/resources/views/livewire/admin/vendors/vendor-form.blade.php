<form wire:submit.prevent="save" class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="md:col-span-2">
            <label class="mt-label">Vendor name <span class="text-red-500">*</span></label>
            <input type="text" wire:model="name" class="mt-input" />
            @error('name') <p class="mt-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mt-label">Vendor code <span class="text-ink-400 text-xs">(unique short code)</span></label>
            <input type="text" wire:model="code" placeholder="e.g. AIR-IND" class="mt-input uppercase" />
            @error('code') <p class="mt-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mt-label">Contact person</label>
            <input type="text" wire:model="contact_person" class="mt-input" />
            @error('contact_person') <p class="mt-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mt-label">Email</label>
            <input type="email" wire:model="email" class="mt-input" />
            @error('email') <p class="mt-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mt-label">Phone</label>
            <input type="tel" wire:model="phone" class="mt-input" />
            @error('phone') <p class="mt-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mt-label">GSTIN</label>
            <input type="text" wire:model="gstin" maxlength="15" placeholder="27ABCDE1234F1Z5" class="mt-input uppercase" />
            @error('gstin') <p class="mt-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mt-label">Payment terms (days)</label>
            <input type="number" wire:model="payment_terms_days" min="0" max="365" class="mt-input" />
            <p class="mt-help">0 = immediate payment.</p>
            @error('payment_terms_days') <p class="mt-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="mt-label">Address</label>
        <textarea wire:model="address" rows="2" class="mt-textarea"></textarea>
        @error('address') <p class="mt-error">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mt-label">Internal notes</label>
        <textarea wire:model="notes" rows="3" class="mt-textarea"></textarea>
        @error('notes') <p class="mt-error">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center justify-between pt-4 border-t border-ink-200/70">
        <a href="{{ $isEdit && $vendor ? route('admin.vendors.show', $vendor->ulid) : route('admin.vendors.index') }}"
           class="mt-btn-ghost mt-btn-sm">Cancel</a>
        <button type="submit" class="mt-btn-primary">
            <span wire:loading.remove wire:target="save">{{ $isEdit ? 'Save changes' : 'Create vendor' }}</span>
            <span wire:loading wire:target="save">Saving…</span>
        </button>
    </div>
</form>
