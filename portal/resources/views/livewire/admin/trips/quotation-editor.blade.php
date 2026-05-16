<div class="bg-white shadow-sm rounded-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Quotation Editor</h2>
            <p class="text-sm text-gray-500">Trip: {{ $quotation->trip->name }} · {{ $quotation->trip->customer->name }}</p>
        </div>
    </div>

    {{-- Lines --}}
    <div class="mb-6">
        <h3 class="font-medium text-gray-700 mb-3">Line Items</h3>
        <div class="space-y-3">
            @foreach($lines as $i => $line)
                <div class="grid grid-cols-12 gap-2 items-end border border-gray-100 rounded-lg p-3">
                    <div class="col-span-2">
                        <label class="text-xs text-gray-500">Type</label>
                        <select wire:model.live="lines.{{ $i }}.line_type" class="w-full border border-gray-300 rounded px-2 py-1 text-sm mt-1">
                            <option value="flight">Flight</option>
                            <option value="hotel">Hotel</option>
                            <option value="package">Package</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-span-4">
                        <label class="text-xs text-gray-500">Description</label>
                        <input wire:model.live="lines.{{ $i }}.description" type="text" class="w-full border border-gray-300 rounded px-2 py-1 text-sm mt-1">
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs text-gray-500">Qty</label>
                        <input wire:model.live="lines.{{ $i }}.quantity" type="number" step="0.01" min="0" class="w-full border border-gray-300 rounded px-2 py-1 text-sm mt-1">
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs text-gray-500">Unit Rate (₹)</label>
                        <input wire:model.live="lines.{{ $i }}.unit_rate" type="number" step="0.01" min="0" class="w-full border border-gray-300 rounded px-2 py-1 text-sm mt-1">
                    </div>
                    @if(auth()->user()?->isAdmin())
                        <div class="col-span-1">
                            <label class="text-xs text-gray-500">Cost (₹)</label>
                            <input wire:model.live="lines.{{ $i }}.purchase_cost" type="number" step="0.01" min="0" class="w-full border border-gray-300 rounded px-2 py-1 text-sm mt-1">
                        </div>
                    @endif
                    <div class="col-span-1 flex justify-end">
                        <button wire:click="removeLine({{ $i }})" class="text-red-400 hover:text-red-600 text-sm mt-5">×</button>
                    </div>
                </div>
            @endforeach
        </div>
        <button wire:click="addLine" class="mt-3 text-sm text-blue-600 hover:text-blue-800">+ Add Line</button>
    </div>

    {{-- Meta --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
            <label class="text-sm font-medium text-gray-700">Service Type</label>
            <select wire:model.live="service_type" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1">
                <option value="package">Package</option>
                <option value="flight">Flight</option>
                <option value="hotel">Hotel</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div>
            <label class="text-sm font-medium text-gray-700">Discount (₹)</label>
            <input wire:model.live="discount_amount" type="number" step="0.01" min="0" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1">
        </div>
        <div>
            <label class="text-sm font-medium text-gray-700">Customer State</label>
            <input wire:model.live="customer_state" type="text" placeholder="e.g. Maharashtra" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1">
        </div>
    </div>

    {{-- Totals --}}
    <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <h3 class="font-medium text-gray-700 mb-3">Totals</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>₹{{ number_format((float)$totals['subtotal'], 2) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Discount</span><span>-₹{{ number_format((float)$totals['discount_amount'], 2) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">CGST</span><span>₹{{ number_format((float)$totals['cgst'], 2) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">SGST</span><span>₹{{ number_format((float)$totals['sgst'], 2) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">IGST</span><span>₹{{ number_format((float)$totals['igst'], 2) }}</span></div>
            <div class="flex justify-between font-bold border-t pt-2 col-span-full"><span>Grand Total</span><span class="text-blue-700">₹{{ number_format((float)$totals['grand_total'], 2) }}</span></div>
        </div>
    </div>

    {{-- Notes --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
            <label class="text-sm font-medium text-gray-700">Terms</label>
            <textarea wire:model="terms" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1"></textarea>
        </div>
        <div>
            <label class="text-sm font-medium text-gray-700">Customer Notes</label>
            <textarea wire:model="customer_notes" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1"></textarea>
        </div>
    </div>

    <div class="flex gap-3">
        <button wire:click="save" class="px-5 py-2 bg-blue-700 text-white text-sm rounded-md hover:bg-blue-800">Save</button>
        <button wire:click="saveAndSend" class="px-5 py-2 bg-green-700 text-white text-sm rounded-md hover:bg-green-800">Save & Mark Sent</button>
        <a href="{{ route('admin.trips.show', $quotation->trip->ulid) }}" class="px-5 py-2 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200">Cancel</a>
    </div>
</div>
