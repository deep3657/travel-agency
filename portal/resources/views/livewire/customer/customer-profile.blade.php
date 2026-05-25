<div>
    <x-page-header title="My Profile" subtitle="Keep your details up to date for accurate bookings and vouchers." />

    @if($errors->any())
        <div class="mt-alert-error mb-4">
            <svg class="h-5 w-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/></svg>
            <div>@foreach($errors->all() as $err)<p>{{ $err }}</p>@endforeach</div>
        </div>
    @endif

    <div class="mt-card">
        <div class="mt-card-header">
            <div>
                <h3 class="font-semibold text-ink-900">Personal information</h3>
                <p class="text-xs text-ink-500 mt-0.5">Used in your bookings, vouchers and travel documents.</p>
            </div>
        </div>
        <div class="mt-card-body grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="mt-label">Full name</label>
                <input wire:model="name" type="text" class="mt-input" placeholder="e.g. Rohan Sharma">
                @error('name')<p class="mt-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mt-label">Phone</label>
                <input wire:model="phone" type="tel" class="mt-input" placeholder="+91 98765 43210">
                @error('phone')<p class="mt-error">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label class="mt-label">Address</label>
                <textarea wire:model="address_line1" rows="2" class="mt-textarea" placeholder="Street, area"></textarea>
                @error('address_line1')<p class="mt-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mt-label">City</label>
                <input wire:model="city" type="text" class="mt-input" placeholder="e.g. Mumbai">
            </div>
            <div>
                <label class="mt-label">State</label>
                <input wire:model="state" type="text" class="mt-input" placeholder="e.g. Maharashtra">
            </div>
            <div class="sm:col-span-2">
                <label class="mt-label">Country</label>
                <input wire:model="country" type="text" class="mt-input">
            </div>
        </div>
        <div class="px-5 py-4 border-t border-ink-200/70 flex justify-end">
            <button wire:click="save" type="button" class="mt-btn-primary"
                    wire:loading.attr="disabled" wire:target="save">
                <svg wire:loading wire:target="save" class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity="0.25"/><path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
                Save changes
            </button>
        </div>
    </div>
</div>
