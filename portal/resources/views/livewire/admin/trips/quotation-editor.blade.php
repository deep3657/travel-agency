<div class="mt-card mt-card-body">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-ink-900">Quotation editor</h2>
            <p class="text-sm text-ink-500 mt-1">Trip: {{ $quotation->trip->name }} · {{ $quotation->trip->customer->name }}</p>
        </div>
    </div>

    {{-- Lines --}}
    <div class="mb-6">
        <h3 class="font-medium text-ink-700 mb-3">Line items</h3>
        <div class="space-y-3">
            @foreach($lines as $i => $line)
                <div class="grid grid-cols-12 gap-2 items-end border border-ink-200 rounded-lg p-3 bg-ink-50/40">
                    <div class="col-span-2">
                        <label class="text-xs text-ink-500">Type</label>
                        <select wire:model.live="lines.{{ $i }}.line_type" class="mt-select mt-1">
                            <option value="flight">Flight</option>
                            <option value="hotel">Hotel</option>
                            <option value="package">Package</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-span-4">
                        <label class="text-xs text-ink-500">Description</label>
                        <input wire:model.live="lines.{{ $i }}.description" type="text" class="mt-input mt-1">
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs text-ink-500">Qty</label>
                        <input wire:model.live="lines.{{ $i }}.quantity" type="number" step="0.01" min="0" class="mt-input mt-1">
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs text-ink-500">Unit rate (₹)</label>
                        <input wire:model.live="lines.{{ $i }}.unit_rate" type="number" step="0.01" min="0" class="mt-input mt-1">
                    </div>
                    @if(auth()->user()?->isAdmin())
                        <div class="col-span-1">
                            <label class="text-xs text-ink-500">Cost (₹)</label>
                            <input wire:model.live="lines.{{ $i }}.purchase_cost" type="number" step="0.01" min="0" class="mt-input mt-1">
                        </div>
                    @endif
                    <div class="col-span-1 flex justify-end">
                        <button wire:click="removeLine({{ $i }})" class="text-red-400 hover:text-red-600 text-lg mt-5">×</button>
                    </div>
                </div>
            @endforeach
        </div>
        <button wire:click="addLine" class="mt-3 text-sm text-brand-700 hover:text-brand-800 hover:underline">+ Add line</button>
    </div>

    {{-- Meta --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div>
            <label class="mt-label">Service type</label>
            <select wire:model.live="service_type" class="mt-select">
                <option value="package">Package</option>
                <option value="flight">Flight</option>
                <option value="hotel">Hotel</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div>
            <label class="mt-label">Discount (₹)</label>
            <input wire:model.live="discount_amount" type="number" step="0.01" min="0" class="mt-input">
        </div>
        <div>
            <label class="mt-label">Customer state</label>
            <input wire:model.live="customer_state" type="text" placeholder="e.g. Maharashtra" class="mt-input">
        </div>
    </div>

    {{-- Totals --}}
    <div class="bg-ink-50 rounded-lg p-4 mb-6 border border-ink-100">
        <h3 class="font-medium text-ink-700 mb-3">Totals</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm">
            <div class="flex justify-between"><span class="text-ink-500">Subtotal</span><span>₹{{ number_format((float)$totals['subtotal'], 2) }}</span></div>
            <div class="flex justify-between"><span class="text-ink-500">Discount</span><span>-₹{{ number_format((float)$totals['discount_amount'], 2) }}</span></div>
            <div class="flex justify-between"><span class="text-ink-500">CGST</span><span>₹{{ number_format((float)$totals['cgst'], 2) }}</span></div>
            <div class="flex justify-between"><span class="text-ink-500">SGST</span><span>₹{{ number_format((float)$totals['sgst'], 2) }}</span></div>
            <div class="flex justify-between"><span class="text-ink-500">IGST</span><span>₹{{ number_format((float)$totals['igst'], 2) }}</span></div>
            <div class="flex justify-between font-bold border-t border-ink-200 pt-2 col-span-full"><span class="text-ink-900">Grand total</span><span class="text-brand-700">₹{{ number_format((float)$totals['grand_total'], 2) }}</span></div>
        </div>
    </div>

    {{-- Notes --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div>
            <label class="mt-label">Terms</label>
            <textarea wire:model="terms" rows="3" class="mt-textarea"></textarea>
        </div>
        <div>
            <label class="mt-label">Customer notes</label>
            <textarea wire:model="customer_notes" rows="3" class="mt-textarea"></textarea>
        </div>
    </div>

    <div class="flex gap-3 pt-4 border-t border-ink-200/70">
        <button wire:click="save" class="mt-btn-primary">Save</button>
        <button wire:click="saveAndSend" class="mt-btn-accent">Save &amp; mark sent</button>
        <a href="{{ route('admin.trips.show', $quotation->trip->ulid) }}" class="mt-btn-secondary">Cancel</a>
    </div>
</div>
