<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.bookings.index') }}" class="text-gray-400 hover:text-gray-600">← Bookings</a>
            <h2 class="font-semibold text-xl text-gray-800">{{ $booking->booking_ref }}</h2>
        </div>
    </x-slot>
    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-6">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-2xl font-bold">{{ $booking->booking_ref }}</div>
                    <div class="text-gray-500">{{ $booking->customer->name }} · {{ ucfirst($booking->booking_type) }}</div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.bookings.edit', $booking->ulid) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm">Edit</a>
                    <form method="POST" action="{{ route('admin.vouchers.generate', $booking->ulid) }}">
                        @csrf
                        <button class="px-4 py-2 bg-blue-700 text-white rounded-md text-sm">Generate Voucher</button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div><dt class="text-gray-500">Status</dt><dd class="font-medium">{{ ucfirst($booking->status) }}</dd></div>
                <div><dt class="text-gray-500">Payment</dt><dd>{{ ucfirst($booking->payment_status) }}</dd></div>
                <div><dt class="text-gray-500">Vendor</dt><dd>{{ $booking->vendor?->name ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Agency PNR</dt><dd>{{ $booking->agency_pnr ?? '—' }}</dd></div>
                @can('seeFinancials', $booking)
                    <div><dt class="text-gray-500">Sale Amount</dt><dd>₹{{ number_format($booking->sale_amount?->toRupees() ?? 0) }}</dd></div>
                    <div><dt class="text-gray-500">Purchase Cost</dt><dd>₹{{ number_format($booking->purchase_cost?->toRupees() ?? 0) }}</dd></div>
                    <div><dt class="text-gray-500">Customer Payment Due</dt><dd>{{ $booking->customer_payment_due?->format('d M Y') ?? '—' }}</dd></div>
                    <div><dt class="text-gray-500">Vendor Payment Due</dt><dd>{{ $booking->vendor_payment_due?->format('d M Y') ?? '—' }}</dd></div>
                @endcan
            </div>

            @if($booking->booking_type === 'flight' && $booking->flight_data)
                <div class="border rounded-lg p-4">
                    <h3 class="font-semibold mb-2">Flight Details</h3>
                    <pre class="text-xs text-gray-600 whitespace-pre-wrap">{{ json_encode($booking->flight_data, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @elseif($booking->booking_type === 'hotel' && $booking->hotel_data)
                <div class="border rounded-lg p-4">
                    <h3 class="font-semibold mb-2">Hotel Details</h3>
                    <pre class="text-xs text-gray-600 whitespace-pre-wrap">{{ json_encode($booking->hotel_data, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @elseif($booking->booking_type === 'package' && $booking->package_data)
                <div class="border rounded-lg p-4">
                    <h3 class="font-semibold mb-2">Package Details</h3>
                    <pre class="text-xs text-gray-600 whitespace-pre-wrap">{{ json_encode($booking->package_data, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endif

            {{-- Passengers --}}
            <div>
                <h3 class="font-semibold mb-2">Passengers</h3>
                @forelse($booking->passengers as $p)
                    <div class="flex items-center gap-3 py-2 border-b last:border-0">
                        <span class="text-sm font-medium">{{ $p->title }} {{ $p->first_name }} {{ $p->last_name }}</span>
                        @if($p->pivot->is_lead)<span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 text-xs rounded">Lead</span>@endif
                        @if($p->passport_number)<span class="text-xs text-gray-500">Passport: {{ $p->passport_number }}</span>@endif
                    </div>
                @empty
                    <p class="text-sm text-gray-400">No passengers linked.</p>
                @endforelse
            </div>

            {{-- Documents --}}
            <div>
                <h3 class="font-semibold mb-2">Documents</h3>
                @forelse($booking->documents as $doc)
                    <div class="flex items-center justify-between py-2 border-b last:border-0">
                        <span class="text-sm">{{ ucfirst(str_replace('_', ' ', $doc->doc_type)) }} v{{ $doc->version_number }}</span>
                        <a href="{{ URL::signedRoute('files.download', ['token' => encrypt($doc->ulid)]) }}" class="text-blue-600 text-sm">Download</a>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">No documents.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
