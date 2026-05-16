<div class="space-y-6">
    <div class="bg-white shadow-sm rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-4">{{ $bookingId ? 'Edit Booking' : 'New Booking' }}</h3>
        @if($errors->any())
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-3">
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Trip</label>
                <select wire:model="trip_id" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="">— Select Trip —</option>
                    @foreach($trips as $t)
                        <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->customer->name }})</option>
                    @endforeach
                </select>
                @error('trip_id')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Booking Type</label>
                <select wire:model.live="booking_type" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="flight">Flight</option>
                    <option value="hotel">Hotel</option>
                    <option value="package">Package</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Vendor</label>
                <select wire:model="vendor_id" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="">— No Vendor —</option>
                    @foreach($vendors as $v)
                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Agency PNR</label>
                <input wire:model="agency_pnr" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Vendor PNR</label>
                <input wire:model="vendor_pnr" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sale Amount (₹)</label>
                <input wire:model="sale_amount" type="number" step="0.01" min="0" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                @error('sale_amount')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            @if($this->showFinancials)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Cost (₹)</label>
                    <input wire:model="purchase_cost" type="number" step="0.01" min="0" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vendor Payment Due</label>
                    <input wire:model="vendor_payment_due" type="date" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                </div>
            @endif
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer Payment Due</label>
                <input wire:model="customer_payment_due" type="date" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="pending_confirmation">Pending Confirmation</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                <select wire:model="payment_status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="unpaid">Unpaid</option>
                    <option value="partial">Partial</option>
                    <option value="paid">Paid</option>
                </select>
            </div>
        </div>

        {{-- Type-specific data --}}
        @if($booking_type === 'flight')
            <div class="mt-4 border-t pt-4">
                <h4 class="font-medium mb-3">Flight Details</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="text-sm text-gray-600">Airline</label><input wire:model="flight_data.airline" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1"></div>
                    <div><label class="text-sm text-gray-600">Flight No</label><input wire:model="flight_data.flight_no" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1"></div>
                    <div><label class="text-sm text-gray-600">Origin</label><input wire:model="flight_data.origin" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1"></div>
                    <div><label class="text-sm text-gray-600">Destination</label><input wire:model="flight_data.destination" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1"></div>
                    <div><label class="text-sm text-gray-600">Departure</label><input wire:model="flight_data.departure_datetime" type="datetime-local" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1"></div>
                    <div><label class="text-sm text-gray-600">Arrival</label><input wire:model="flight_data.arrival_datetime" type="datetime-local" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1"></div>
                    <div><label class="text-sm text-gray-600">Class</label><input wire:model="flight_data.class" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1"></div>
                    <div><label class="text-sm text-gray-600">Baggage</label><input wire:model="flight_data.baggage" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1"></div>
                </div>
            </div>
        @elseif($booking_type === 'hotel')
            <div class="mt-4 border-t pt-4">
                <h4 class="font-medium mb-3">Hotel Details</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="text-sm text-gray-600">Hotel Name</label><input wire:model="hotel_data.hotel_name" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1"></div>
                    <div><label class="text-sm text-gray-600">Room Type</label><input wire:model="hotel_data.room_type" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1"></div>
                    <div><label class="text-sm text-gray-600">Check-In</label><input wire:model="hotel_data.check_in" type="date" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1"></div>
                    <div><label class="text-sm text-gray-600">Check-Out</label><input wire:model="hotel_data.check_out" type="date" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1"></div>
                    <div><label class="text-sm text-gray-600">Adults</label><input wire:model="hotel_data.adults" type="number" min="1" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1"></div>
                    <div><label class="text-sm text-gray-600">Children</label><input wire:model="hotel_data.children" type="number" min="0" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1"></div>
                </div>
            </div>
        @elseif($booking_type === 'package')
            <div class="mt-4 border-t pt-4">
                <h4 class="font-medium mb-3">Package Details</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="text-sm text-gray-600">Package Name</label><input wire:model="package_data.package_name" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1"></div>
                    <div><label class="text-sm text-gray-600">Travel Start</label><input wire:model="package_data.travel_start" type="date" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1"></div>
                    <div><label class="text-sm text-gray-600">Travel End</label><input wire:model="package_data.travel_end" type="date" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1"></div>
                    <div class="col-span-2"><label class="text-sm text-gray-600">Inclusions Summary</label><textarea wire:model="package_data.inclusions_summary" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mt-1"></textarea></div>
                </div>
            </div>
        @endif

        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
            <textarea wire:model="notes" rows="2" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"></textarea>
        </div>

        <div class="flex gap-3 mt-6 pt-4 border-t">
            <button wire:click="save" class="px-6 py-2 bg-blue-700 text-white text-sm rounded-md hover:bg-blue-800">Save Booking</button>
            <a href="{{ route('admin.bookings.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200">Cancel</a>
        </div>
    </div>
</div>
