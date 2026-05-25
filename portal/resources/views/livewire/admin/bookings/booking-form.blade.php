<div class="space-y-6">
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
                <select wire:model="trip_id" class="mt-select">
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
                <label class="mt-label">Vendor PNR</label>
                <input wire:model="vendor_pnr" type="text" class="mt-input">
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
                    <div><label class="mt-label">Airline</label><input wire:model="flight_data.airline" type="text" class="mt-input"></div>
                    <div><label class="mt-label">Flight no</label><input wire:model="flight_data.flight_no" type="text" class="mt-input"></div>
                    <div><label class="mt-label">Origin</label><input wire:model="flight_data.origin" type="text" class="mt-input"></div>
                    <div><label class="mt-label">Destination</label><input wire:model="flight_data.destination" type="text" class="mt-input"></div>
                    <div><label class="mt-label">Departure</label><input wire:model="flight_data.departure_datetime" type="datetime-local" class="mt-input"></div>
                    <div><label class="mt-label">Arrival</label><input wire:model="flight_data.arrival_datetime" type="datetime-local" class="mt-input"></div>
                    <div><label class="mt-label">Class</label><input wire:model="flight_data.class" type="text" class="mt-input"></div>
                    <div><label class="mt-label">Baggage</label><input wire:model="flight_data.baggage" type="text" class="mt-input"></div>
                </div>
            </div>
        @elseif($booking_type === 'hotel')
            <div class="mt-6 border-t border-ink-200/70 pt-5">
                <h4 class="font-medium text-ink-900 mb-3">Hotel details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="mt-label">Hotel name</label><input wire:model="hotel_data.hotel_name" type="text" class="mt-input"></div>
                    <div><label class="mt-label">Room type</label><input wire:model="hotel_data.room_type" type="text" class="mt-input"></div>
                    <div><label class="mt-label">Check-in</label><input wire:model="hotel_data.check_in" type="date" class="mt-input"></div>
                    <div><label class="mt-label">Check-out</label><input wire:model="hotel_data.check_out" type="date" class="mt-input"></div>
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
