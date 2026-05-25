<div class="mt-card mt-card-body">
    @if($step === 1)
        <h3 class="font-semibold text-lg text-ink-900 mb-1">Step 1 of 2 — Upload supplier document</h3>
        <p class="text-sm text-ink-500 mb-4">
            Upload the supplier-issued PDF (Tripjack, TBO, airline / hotel portal, etc.).
            If you choose AI extraction, the booking form will be prefilled automatically.
        </p>
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
                    <option value="ai">AI auto-extract (recommended)</option>
                    <option value="manual">Manual — I'll fill the form myself</option>
                </select>
                <p class="text-xs text-ink-500 mt-1">
                    AI mode reads the document and prefills the booking form. Low-confidence
                    fields are highlighted so you can verify them.
                </p>
            </div>
            <div>
                <label class="mt-label">Supplier name <span class="text-ink-400 font-normal">(optional)</span></label>
                <input wire:model="supplier_name" type="text" placeholder="e.g. Tripjack, IndiGo, Taj Hotels" class="mt-input">
            </div>
            <div>
                <label class="mt-label">File</label>
                <input wire:model="file" type="file" accept=".pdf,.jpg,.jpeg,.png"
                       class="block w-full text-sm text-ink-700 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-brand-700 file:text-white hover:file:bg-brand-800">
                <p class="text-xs text-ink-500 mt-1">PDF, JPG or PNG · max 10 MB</p>
                @if($file && !$errors->has('file'))
                    <p class="text-xs text-emerald-600 mt-1">✓ File selected: {{ $file->getClientOriginalName() }}</p>
                @endif
            </div>
            <button wire:click="nextStep" class="mt-btn-primary">Next →</button>
        </div>
    @else
        <h3 class="font-semibold text-lg text-ink-900 mb-1">Step 2 of 2 — What's next?</h3>
        <p class="text-sm text-ink-500 mb-4">Choose how this document should be used.</p>

        @if($errors->any())
            <div class="mt-alert-error mb-4">
                <div>@foreach($errors->all() as $err)<p>{{ $err }}</p>@endforeach</div>
            </div>
        @endif

        {{-- Action picker --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-5">
            <label class="cursor-pointer rounded-lg border-2 p-4 transition
                {{ $nextAction === 'create_booking' ? 'border-brand-700 bg-brand-50' : 'border-ink-200 hover:border-ink-300' }}">
                <input type="radio" wire:model.live="nextAction" value="create_booking" class="sr-only">
                <div class="font-semibold text-ink-900 text-sm">Create new booking</div>
                <p class="text-xs text-ink-500 mt-1">
                    Use this document to create a booking
                    @if($extraction_mode === 'ai')<span class="text-brand-700 font-medium">— AI will prefill the form</span>@endif.
                </p>
            </label>
            <label class="cursor-pointer rounded-lg border-2 p-4 transition
                {{ $nextAction === 'attach_existing' ? 'border-brand-700 bg-brand-50' : 'border-ink-200 hover:border-ink-300' }}">
                <input type="radio" wire:model.live="nextAction" value="attach_existing" class="sr-only">
                <div class="font-semibold text-ink-900 text-sm">Attach to existing booking</div>
                <p class="text-xs text-ink-500 mt-1">Link this document to a booking I've already created.</p>
            </label>
            <label class="cursor-pointer rounded-lg border-2 p-4 transition
                {{ $nextAction === 'skip' ? 'border-brand-700 bg-brand-50' : 'border-ink-200 hover:border-ink-300' }}">
                <input type="radio" wire:model.live="nextAction" value="skip" class="sr-only">
                <div class="font-semibold text-ink-900 text-sm">Just upload</div>
                <p class="text-xs text-ink-500 mt-1">Save the file now and link it later.</p>
            </label>
        </div>

        {{-- Branch-specific picker --}}
        @if($nextAction === 'create_booking')
            @if($this->selectedTrip)
                <div class="rounded-lg border border-brand-200 bg-brand-50 px-4 py-3 text-sm">
                    <p class="text-ink-600">New booking will be created under trip:</p>
                    <p class="font-semibold text-ink-900 mt-0.5">
                        {{ $this->selectedTrip->name }}
                        @if($this->selectedTrip->customer)
                            <span class="text-ink-600 font-normal">— {{ $this->selectedTrip->customer->name }}</span>
                        @endif
                    </p>
                    <button type="button" wire:click="$set('trip_id', null)" class="text-xs text-brand-700 hover:text-brand-800 hover:underline mt-1">
                        Change trip
                    </button>
                </div>
            @else
                <div class="mb-4">
                    <label class="mt-label">Trip</label>
                    <select wire:model="trip_id" class="mt-select">
                        <option value="">— Choose the trip this booking is for —</option>
                        @foreach($this->recentTrips as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}@if($t->customer) — {{ $t->customer->name }}@endif</option>
                        @endforeach
                    </select>
                    @error('trip_id') <p class="mt-error">{{ $message }}</p> @enderror
                    @if($this->recentTrips->isEmpty())
                        <p class="text-xs text-ink-400 mt-1">
                            No trips yet — <a href="{{ route('admin.trips.create') }}" class="text-brand-700 hover:underline">create one</a> first.
                        </p>
                    @endif
                </div>
            @endif
        @elseif($nextAction === 'attach_existing')
            @if($this->selectedBooking)
                <div class="rounded-lg border border-brand-200 bg-brand-50 px-4 py-3 text-sm">
                    <p class="text-ink-600">This document will be attached to:</p>
                    <p class="font-mono font-semibold text-ink-900 mt-0.5">
                        {{ $this->selectedBooking->booking_ref }}
                        @if($this->selectedBooking->customer)
                            <span class="text-ink-600 font-sans font-normal">— {{ $this->selectedBooking->customer->name }}</span>
                        @endif
                    </p>
                    <button type="button" wire:click="$set('booking_id', null)" class="text-xs text-brand-700 hover:text-brand-800 hover:underline mt-1">
                        Change booking
                    </button>
                </div>
            @else
                <div class="mb-4">
                    <label class="mt-label">Booking reference</label>
                    <select wire:model="booking_id" class="mt-select">
                        <option value="">— Choose a booking —</option>
                        @foreach($this->recentBookings as $b)
                            <option value="{{ $b->id }}">{{ $b->booking_ref }}@if($b->customer) — {{ $b->customer->name }}@endif</option>
                        @endforeach
                    </select>
                    @error('booking_id') <p class="mt-error">{{ $message }}</p> @enderror
                    @if($this->recentBookings->isEmpty())
                        <p class="text-xs text-ink-400 mt-1">No bookings yet.</p>
                    @endif
                </div>
            @endif
        @endif

        <div class="flex gap-3 mt-6 pt-4 border-t border-ink-100">
            <button wire:click="save" class="mt-btn-accent"
                    wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">
                    @if($nextAction === 'create_booking')
                        @if($extraction_mode === 'ai')
                            Upload &amp; extract with AI →
                        @else
                            Upload &amp; create booking →
                        @endif
                    @elseif($nextAction === 'attach_existing')
                        Upload &amp; attach
                    @else
                        Upload &amp; save
                    @endif
                </span>
                <span wire:loading wire:target="save">
                    @if($extraction_mode === 'ai' && $nextAction === 'create_booking')
                        Extracting fields with AI — this can take 5–10 seconds…
                    @else
                        Uploading…
                    @endif
                </span>
            </button>
            <button type="button" wire:click="$set('step', 1)" class="mt-btn-secondary">← Back</button>
        </div>
    @endif
</div>
