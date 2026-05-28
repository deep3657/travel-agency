<div class="space-y-6">
    @php
        $isLow = fn (string $field): bool => in_array($field, $lowConfidenceFields, true);
        $inputClass = fn (string $field): string => $isLow($field)
            ? 'mt-input ring-2 ring-amber-300 focus:ring-amber-400 bg-amber-50/40'
            : 'mt-input';
        $selectClass = fn (string $field): string => $isLow($field)
            ? 'mt-select ring-2 ring-amber-300 focus:ring-amber-400 bg-amber-50/40'
            : 'mt-select';
        $lowChip = '<span class="ml-2 text-[10px] uppercase tracking-wide font-semibold text-amber-700 bg-amber-100 px-1.5 py-0.5 rounded">Verify</span>';
    @endphp

    @if(! $isEdit && $supplierDocumentId !== null)
        {{-- AI-extraction banner: shown only when this booking is being created
             from a supplier-doc upload via the wizard. --}}
        <div class="mt-card mt-card-body border-l-4 {{ $extractionStatus === 'completed' ? 'border-l-emerald-500 bg-emerald-50/30' : ($extractionStatus === 'failed' ? 'border-l-rose-500 bg-rose-50/30' : 'border-l-violet-500 bg-violet-50/30') }}">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-start gap-3">
                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg
                        {{ $extractionStatus === 'completed' ? 'bg-emerald-100 text-emerald-700' : ($extractionStatus === 'failed' ? 'bg-rose-100 text-rose-700' : 'bg-violet-100 text-violet-700') }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </span>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h4 class="font-semibold text-ink-900">
                                @if($extractionStatus === 'completed')
                                    Prefilled from supplier document via AI
                                @elseif($extractionStatus === 'failed')
                                    AI extraction failed — please fill manually
                                @elseif($extractionStatus === null)
                                    Creating booking from supplier document
                                @else
                                    Extracting fields from supplier document…
                                @endif
                            </h4>
                            @if($extractionProvider)
                                <span class="text-[10px] uppercase tracking-wide font-semibold text-violet-700 bg-violet-100 px-1.5 py-0.5 rounded">{{ $extractionProvider }}</span>
                            @endif
                        </div>
                        <p class="text-sm text-ink-600 mt-1">
                            Source: <span class="font-mono">{{ $supplierDocumentFilename }}</span>.
                            After you save, the standardised Maruti voucher will be generated automatically.
                        </p>
                        @if(count($lowConfidenceFields) > 0)
                            <p class="text-sm text-amber-800 mt-2 inline-flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <strong>{{ count($lowConfidenceFields) }}</strong>
                                {{ count($lowConfidenceFields) === 1 ? 'field has' : 'fields have' }} low confidence — please verify the highlighted values.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="mt-card mt-card-body">
        <h3 class="text-lg font-semibold text-ink-900 mb-4">{{ $isEdit ? 'Edit booking' : 'New booking' }}</h3>
        @if($errors->any())
            <div class="mt-alert-error mb-4">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="mt-label">Trip</label>
                <select wire:model.live="trip_id" class="mt-select">
                    <option value="">— Select trip —</option>
                    @foreach($this->trips as $t)
                        <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->customer?->name }})</option>
                    @endforeach
                </select>
                @error('trip_id')<p class="mt-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mt-label">Booking type</label>
                <select wire:model.live="booking_type" class="mt-select">
                    <option value="flight">Flight</option>
                    <option value="hotel">Hotel</option>
                    <option value="package">Package</option>
                </select>
            </div>
            <div>
                <label class="mt-label">Vendor</label>
                <select wire:model="vendor_id" class="mt-select">
                    <option value="">— No vendor —</option>
                    @foreach($this->vendors as $v)
                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mt-label">Agency PNR</label>
                <input wire:model="agency_pnr" type="text" class="mt-input">
            </div>
            <div>
                <label class="mt-label">Vendor PNR @if($isLow('vendor_pnr')) {!! $lowChip !!} @endif</label>
                <input wire:model="vendor_pnr" type="text" class="{{ $inputClass('vendor_pnr') }}">
            </div>
            <div>
                <label class="mt-label">Sale amount (₹)</label>
                <input wire:model="sale_amount" type="number" step="0.01" min="0" class="mt-input">
                @error('sale_amount')<p class="mt-error">{{ $message }}</p>@enderror
            </div>
            @if($this->showFinancials)
                <div>
                    <label class="mt-label">Purchase cost (₹)</label>
                    <input wire:model="purchase_cost" type="number" step="0.01" min="0" class="mt-input">
                </div>
                <div>
                    <label class="mt-label">Vendor payment due</label>
                    <input wire:model="vendor_payment_due" type="date" class="mt-input">
                </div>
            @endif
            <div>
                <label class="mt-label">Customer payment due</label>
                <input wire:model="customer_payment_due" type="date" class="mt-input">
            </div>
            <div>
                <label class="mt-label">Status</label>
                <select wire:model="status" class="mt-select">
                    <option value="pending_confirmation">Pending confirmation</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div>
                <label class="mt-label">Payment status</label>
                <select wire:model="payment_status" class="mt-select">
                    <option value="unpaid">Unpaid</option>
                    <option value="partial">Partial</option>
                    <option value="paid">Paid</option>
                </select>
            </div>
        </div>

        {{-- Type-specific data --}}
        @if($booking_type === 'flight')
            <div class="mt-6 border-t border-ink-200/70 pt-5">
                <h4 class="font-medium text-ink-900 mb-3">Flight details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="mt-label">Airline @if($isLow('flight_data.airline')) {!! $lowChip !!} @endif</label><input wire:model="flight_data.airline" type="text" class="{{ $inputClass('flight_data.airline') }}"></div>
                    <div><label class="mt-label">Flight no @if($isLow('flight_data.flight_no')) {!! $lowChip !!} @endif</label><input wire:model="flight_data.flight_no" type="text" class="{{ $inputClass('flight_data.flight_no') }}"></div>
                    <div><label class="mt-label">Origin @if($isLow('flight_data.origin')) {!! $lowChip !!} @endif</label><input wire:model="flight_data.origin" type="text" class="{{ $inputClass('flight_data.origin') }}"></div>
                    <div><label class="mt-label">Destination @if($isLow('flight_data.destination')) {!! $lowChip !!} @endif</label><input wire:model="flight_data.destination" type="text" class="{{ $inputClass('flight_data.destination') }}"></div>
                    <div><label class="mt-label">Departure @if($isLow('flight_data.departure_datetime')) {!! $lowChip !!} @endif</label><input wire:model="flight_data.departure_datetime" type="datetime-local" class="{{ $inputClass('flight_data.departure_datetime') }}"></div>
                    <div><label class="mt-label">Arrival @if($isLow('flight_data.arrival_datetime')) {!! $lowChip !!} @endif</label><input wire:model="flight_data.arrival_datetime" type="datetime-local" class="{{ $inputClass('flight_data.arrival_datetime') }}"></div>
                    <div><label class="mt-label">Class @if($isLow('flight_data.class')) {!! $lowChip !!} @endif</label><input wire:model="flight_data.class" type="text" class="{{ $inputClass('flight_data.class') }}"></div>
                    <div><label class="mt-label">Baggage</label><input wire:model="flight_data.baggage" type="text" class="mt-input"></div>
                </div>
            </div>
        @elseif($booking_type === 'hotel')
            <div class="mt-6 border-t border-ink-200/70 pt-5">
                <h4 class="font-medium text-ink-900 mb-3">Hotel details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="mt-label">Hotel name @if($isLow('hotel_data.hotel_name')) {!! $lowChip !!} @endif</label><input wire:model="hotel_data.hotel_name" type="text" class="{{ $inputClass('hotel_data.hotel_name') }}"></div>
                    <div><label class="mt-label">Room type @if($isLow('hotel_data.room_type')) {!! $lowChip !!} @endif</label><input wire:model="hotel_data.room_type" type="text" class="{{ $inputClass('hotel_data.room_type') }}"></div>
                    <div><label class="mt-label">Check-in @if($isLow('hotel_data.check_in')) {!! $lowChip !!} @endif</label><input wire:model="hotel_data.check_in" type="date" class="{{ $inputClass('hotel_data.check_in') }}"></div>
                    <div><label class="mt-label">Check-out @if($isLow('hotel_data.check_out')) {!! $lowChip !!} @endif</label><input wire:model="hotel_data.check_out" type="date" class="{{ $inputClass('hotel_data.check_out') }}"></div>
                    <div><label class="mt-label">Adults</label><input wire:model="hotel_data.adults" type="number" min="1" class="mt-input"></div>
                    <div><label class="mt-label">Children</label><input wire:model="hotel_data.children" type="number" min="0" class="mt-input"></div>
                </div>
            </div>
        @elseif($booking_type === 'package')
            <div class="mt-6 border-t border-ink-200/70 pt-5">
                <h4 class="font-medium text-ink-900 mb-3">Package details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="mt-label">Package name</label><input wire:model="package_data.package_name" type="text" class="mt-input"></div>
                    <div><label class="mt-label">Travel start</label><input wire:model="package_data.travel_start" type="date" class="mt-input"></div>
                    <div><label class="mt-label">Travel end</label><input wire:model="package_data.travel_end" type="date" class="mt-input"></div>
                    <div class="md:col-span-2"><label class="mt-label">Inclusions summary</label><textarea wire:model="package_data.inclusions_summary" rows="3" class="mt-textarea"></textarea></div>
                </div>
            </div>
        @endif

        {{-- Passengers --}}
        <div class="mt-6 border-t border-ink-200/70 pt-5">
            <div class="flex items-baseline justify-between mb-3">
                <h4 class="font-medium text-ink-900">Passengers</h4>
                @if($trip_id === null)
                    <span class="text-xs text-ink-500">Select a trip to manage passengers</span>
                @endif
            </div>

            @if($trip_id !== null)
                @if($this->availablePassengers->count() > 0)
                    <div class="mb-4">
                        <div class="text-xs uppercase tracking-wide text-ink-500 mb-2">Existing passengers on this trip</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($this->availablePassengers as $p)
                                <label class="flex items-center gap-2 p-2 border border-ink-200 rounded hover:bg-ink-50/50">
                                    <input type="checkbox" wire:model.live="passengerIds" value="{{ $p->id }}" class="rounded">
                                    <span class="text-sm text-ink-800 flex-1">{{ $p->title }} {{ $p->first_name }} {{ $p->last_name }}</span>
                                    @if(in_array($p->id, $passengerIds))
                                        <label class="flex items-center gap-1 text-xs text-ink-600">
                                            <input type="radio" wire:model="leadPassengerId" value="{{ $p->id }}">
                                            Lead
                                        </label>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(count($newPassengers) > 0)
                    <div class="mb-4">
                        <div class="text-xs uppercase tracking-wide text-ink-500 mb-2">New passengers to add</div>
                        <div class="space-y-2">
                            @foreach($newPassengers as $i => $np)
                                <div class="flex items-center gap-2 p-2 border border-emerald-200 bg-emerald-50/40 rounded flex-wrap">
                                    <span class="text-sm text-ink-800 flex-1 min-w-0">
                                        {{ trim(($np['title'] ?? '').' '.($np['first_name'] ?? '').' '.($np['last_name'] ?? '')) }}
                                    </span>
                                    @if(! empty($np['pax_type']))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-ink-100 text-ink-700 text-[10px] font-semibold uppercase tracking-wide">{{ $np['pax_type'] }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-[10px] font-semibold uppercase tracking-wide">No type</span>
                                    @endif
                                    <button type="button" wire:click="removeNewPassenger({{ $i }})" class="text-xs text-rose-700 hover:text-rose-800 hover:underline">Remove</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <div class="text-xs uppercase tracking-wide text-ink-500 mb-2">Add new passenger</div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2">
                        <select wire:model="newTitle" class="mt-select sm:col-span-2">
                            <option value="">Title</option>
                            <option value="Mr">Mr.</option>
                            <option value="Mrs">Mrs.</option>
                            <option value="Miss">Miss</option>
                            <option value="Master">Master</option>
                        </select>
                        <input wire:model="newFirstName" type="text" placeholder="First name" class="mt-input sm:col-span-4">
                        <input wire:model="newLastName" type="text" placeholder="Last name" class="mt-input sm:col-span-3">
                        <select wire:model="newPaxType" class="mt-select sm:col-span-2">
                            <option value="adult">Adult</option>
                            <option value="child">Child</option>
                            <option value="infant">Infant</option>
                        </select>
                        <button type="button" wire:click="addNewPassenger" class="mt-btn-secondary sm:col-span-1">+ Add</button>
                    </div>
                </div>
            @endif
        </div>

        <div class="mt-6">
            <label class="mt-label">Notes</label>
            <textarea wire:model="notes" rows="2" class="mt-textarea"></textarea>
        </div>

        <div class="flex gap-3 mt-6 pt-4 border-t border-ink-200/70">
            <button wire:click="save" class="mt-btn-primary">Save booking</button>
            <a href="{{ route('admin.bookings.index') }}" class="mt-btn-secondary">Cancel</a>
        </div>
    </div>
</div>
