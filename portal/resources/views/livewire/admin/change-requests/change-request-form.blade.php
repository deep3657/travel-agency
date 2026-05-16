<div class="bg-white shadow-sm rounded-lg p-6">
    <h3 class="font-semibold text-lg mb-4">Change Request — {{ $changeRequest->booking->booking_ref }}</h3>
    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 rounded-md p-3 text-sm text-red-700">
            @foreach($errors->all() as $err)<p>{{ $err }}</p>@endforeach
        </div>
    @endif

    <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Request Type</label>
            <select wire:model="request_type" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                <option value="cancellation">Cancellation</option>
                <option value="amendment">Amendment</option>
                <option value="rebooking">Rebooking</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select wire:model="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                <option value="open">Open</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Assigned To</label>
            <select wire:model="assigned_user_id" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                <option value="">— Unassigned —</option>
                @foreach($agents as $a)
                    <option value="{{ $a->id }}">{{ $a->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Refund Mode</label>
            <select wire:model="refund_mode" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                <option value="">— N/A —</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="credit_note">Credit Note</option>
                <option value="cash">Cash</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Vendor Fee (₹)</label>
            <input wire:model.live="vendor_fee" type="number" step="0.01" min="0" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Refund from Vendor (₹)</label>
            <input wire:model.live="refund_from_vendor" type="number" step="0.01" min="0" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Agency Service Fee (₹)</label>
            <input wire:model.live="agency_service_fee" type="number" step="0.01" min="0" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Net Refund (computed)</label>
            <div class="w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2 text-sm font-mono">₹{{ number_format((float)$this->netRefund, 2) }}</div>
        </div>
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Customer-Facing Summary</label>
        <textarea wire:model="customer_facing_summary" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"></textarea>
    </div>
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Internal Reason</label>
        <textarea wire:model="reason" rows="2" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"></textarea>
    </div>

    <div class="flex gap-3">
        <button wire:click="save" class="px-5 py-2 bg-blue-700 text-white text-sm rounded-md hover:bg-blue-800">Save</button>
        @if($changeRequest->status !== 'completed')
            <button wire:click="complete" wire:confirm="Mark as completed? This will notify the customer." class="px-5 py-2 bg-green-700 text-white text-sm rounded-md hover:bg-green-800">Mark Complete</button>
        @endif
        <a href="{{ route('admin.change-requests.index') }}" class="px-5 py-2 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200">Cancel</a>
    </div>
</div>
