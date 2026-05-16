<div class="bg-white shadow-sm rounded-lg p-6">
    @if($step === 1)
        <h3 class="font-semibold text-lg mb-4">Step 1 of 2 — Upload Document</h3>
        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 rounded-md p-3 text-sm text-red-700">
                @foreach($errors->all() as $err)<p>{{ $err }}</p>@endforeach
            </div>
        @endif
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Document Type</label>
                <select wire:model="doc_type" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="flight">Flight Confirmation</option>
                    <option value="hotel">Hotel Voucher</option>
                    <option value="package">Package Itinerary</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Extraction Mode</label>
                <select wire:model="extraction_mode" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="manual">Manual</option>
                    <option value="ai">AI Auto-Extract</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Supplier Name</label>
                <input wire:model="supplier_name" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">File</label>
                <input wire:model="file" type="file" accept=".pdf,.jpg,.jpeg,.png" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                @if($file && !$errors->has('file'))
                    <p class="text-xs text-green-600 mt-1">✓ File selected: {{ $file->getClientOriginalName() }}</p>
                @endif
            </div>
            <button wire:click="nextStep" class="px-5 py-2 bg-blue-700 text-white text-sm rounded-md hover:bg-blue-800">Next →</button>
        </div>
    @else
        <h3 class="font-semibold text-lg mb-4">Step 2 of 2 — Link to Booking (optional)</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Booking Reference</label>
                <select wire:model="booking_id" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="">— Skip / Link Later —</option>
                    @foreach($bookings as $b)
                        <option value="{{ $b->id }}">{{ $b->booking_ref }} — {{ $b->customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3">
                <button wire:click="save" class="px-5 py-2 bg-green-700 text-white text-sm rounded-md hover:bg-green-800">Upload & Save</button>
                <button wire:click="$set('step', 1)" class="px-5 py-2 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200">← Back</button>
            </div>
        </div>
    @endif
</div>
