<div class="mt-card mt-card-body">
    <h3 class="font-semibold text-lg text-ink-900 mb-4">Change request — {{ $changeRequest->booking->booking_ref }}</h3>
    @if($errors->any())
        <div class="mt-alert-error mb-4">
            <div>@foreach($errors->all() as $err)<p>{{ $err }}</p>@endforeach</div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div>
            <label class="mt-label">Request type</label>
            <select wire:model="request_type" class="mt-select">
                <option value="cancellation">Cancellation</option>
                <option value="amendment">Amendment</option>
                <option value="rebooking">Rebooking</option>
            </select>
        </div>
        <div>
            <label class="mt-label">Status</label>
            <select wire:model="status" class="mt-select">
                <option value="open">Open</option>
                <option value="in_progress">In progress</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <div>
            <label class="mt-label">Assigned to</label>
            <select wire:model="assigned_user_id" class="mt-select">
                <option value="">— Unassigned —</option>
                @foreach($agents as $a)
                    <option value="{{ $a->id }}">{{ $a->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mt-label">Refund mode</label>
            <select wire:model="refund_mode" class="mt-select">
                <option value="">— N/A —</option>
                <option value="bank_transfer">Bank transfer</option>
                <option value="credit_note">Credit note</option>
                <option value="cash">Cash</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div>
            <label class="mt-label">Vendor fee (₹)</label>
            <input wire:model.live="vendor_fee" type="number" step="0.01" min="0" class="mt-input">
        </div>
        <div>
            <label class="mt-label">Refund from vendor (₹)</label>
            <input wire:model.live="refund_from_vendor" type="number" step="0.01" min="0" class="mt-input">
        </div>
        <div>
            <label class="mt-label">Agency service fee (₹)</label>
            <input wire:model.live="agency_service_fee" type="number" step="0.01" min="0" class="mt-input">
        </div>
        <div>
            <label class="mt-label">Net refund (computed)</label>
            <div class="w-full rounded-lg border border-ink-200 bg-ink-50 px-3 py-2 text-sm font-mono text-ink-800">₹{{ number_format((float)$this->netRefund, 2) }}</div>
        </div>
    </div>

    <div class="mb-4">
        <label class="mt-label">Customer-facing summary</label>
        <textarea wire:model="customer_facing_summary" rows="3" class="mt-textarea"></textarea>
    </div>
    <div class="mb-4">
        <label class="mt-label">Internal reason</label>
        <textarea wire:model="reason" rows="2" class="mt-textarea"></textarea>
    </div>

    <div class="flex gap-3 pt-4 border-t border-ink-200/70">
        <button wire:click="save" class="mt-btn-primary">Save</button>
        @if($changeRequest->status !== 'completed')
            <button wire:click="complete" wire:confirm="Mark as completed? This will notify the customer." class="mt-btn-accent">Mark complete</button>
        @endif
        <a href="{{ route('admin.change-requests.index') }}" class="mt-btn-secondary">Cancel</a>
    </div>
</div>
