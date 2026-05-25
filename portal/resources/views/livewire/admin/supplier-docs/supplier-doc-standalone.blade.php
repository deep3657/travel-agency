<div class="mt-card mt-card-body">
    @if($step === 1)
        <h3 class="font-semibold text-lg text-ink-900 mb-4">Step 1 of 2 — Upload document</h3>
        @if($errors->any())
            <div class="mt-alert-error mb-4">
                <div>@foreach($errors->all() as $err)<p>{{ $err }}</p>@endforeach</div>
            </div>
        @endif
        <div class="space-y-4">
            <div>
                <label class="mt-label">Document type</label>
                <select wire:model="doc_type" class="mt-select">
                    <option value="flight">Flight confirmation</option>
                    <option value="hotel">Hotel voucher</option>
                    <option value="package">Package itinerary</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div>
                <label class="mt-label">Extraction mode</label>
                <select wire:model="extraction_mode" class="mt-select">
                    <option value="manual">Manual</option>
                    <option value="ai">AI auto-extract</option>
                </select>
            </div>
            <div>
                <label class="mt-label">Supplier name</label>
                <input wire:model="supplier_name" type="text" class="mt-input">
            </div>
            <div>
                <label class="mt-label">File</label>
                <input wire:model="file" type="file" accept=".pdf,.jpg,.jpeg,.png"
                       class="block w-full text-sm text-ink-700 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-brand-700 file:text-white hover:file:bg-brand-800">
                @if($file && !$errors->has('file'))
                    <p class="text-xs text-emerald-600 mt-1">✓ File selected: {{ $file->getClientOriginalName() }}</p>
                @endif
            </div>
            <button wire:click="nextStep" class="mt-btn-primary">Next →</button>
        </div>
    @else
        <h3 class="font-semibold text-lg text-ink-900 mb-4">Step 2 of 2 — Link to booking (optional)</h3>
        @if($errors->any())
            <div class="mt-alert-error mb-4">
                <div>@foreach($errors->all() as $err)<p>{{ $err }}</p>@endforeach</div>
            </div>
        @endif
        <div class="space-y-4">
            <div>
                <label class="mt-label">Booking reference</label>
                <select wire:model="booking_id" class="mt-select">
                    <option value="">— Skip / link later —</option>
                    @foreach($this->recentBookings as $b)
                        <option value="{{ $b->id }}">{{ $b->booking_ref }}@if($b->customer) — {{ $b->customer->name }}@endif</option>
                    @endforeach
                </select>
                @error('booking_id') <p class="mt-error">{{ $message }}</p> @enderror
                @if($this->recentBookings->isEmpty())
                    <p class="text-xs text-ink-400 mt-1">No bookings yet — you can always link this document to a booking later.</p>
                @endif
            </div>
            <div class="flex gap-3">
                <button wire:click="save" class="mt-btn-accent"
                        wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save">Upload &amp; save</span>
                    <span wire:loading wire:target="save">Uploading…</span>
                </button>
                <button type="button" wire:click="$set('step', 1)" class="mt-btn-secondary">← Back</button>
            </div>
        </div>
    @endif
</div>
