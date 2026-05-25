<x-app-layout>
    <x-slot name="header">
        <x-page-header
            :title="$booking->booking_ref"
            :subtitle="$booking->customer->name . ' · ' . ucfirst($booking->booking_type)"
            :breadcrumbs="[
                ['label' => 'Bookings', 'href' => route('admin.bookings.index')],
                ['label' => $booking->booking_ref],
            ]">
            <a href="{{ route('admin.bookings.edit', $booking->ulid) }}" class="mt-btn-secondary mt-btn-sm">Edit</a>
            <form method="POST" action="{{ route('admin.vouchers.generate', $booking->ulid) }}">
                @csrf
                <button class="mt-btn-primary mt-btn-sm">Generate voucher</button>
            </form>
        </x-page-header>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />

            <div class="mt-card mt-card-body space-y-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-4">
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-ink-500">Status</dt>
                        <dd class="mt-1"><x-status-pill :status="$booking->status" /></dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-ink-500">Payment</dt>
                        <dd class="mt-1"><x-status-pill :status="$booking->payment_status" /></dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-ink-500">Vendor</dt>
                        <dd class="text-sm text-ink-800 mt-0.5">{{ $booking->vendor?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-ink-500">Agency PNR</dt>
                        <dd class="text-sm text-ink-800 mt-0.5 font-mono">{{ $booking->agency_pnr ?? '—' }}</dd>
                    </div>
                    @can('seeFinancials', $booking)
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-ink-500">Sale amount</dt>
                            <dd class="text-sm text-ink-900 font-semibold mt-0.5">₹{{ number_format($booking->sale_amount?->toRupees() ?? 0) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-ink-500">Purchase cost</dt>
                            <dd class="text-sm text-ink-800 mt-0.5">₹{{ number_format($booking->purchase_cost?->toRupees() ?? 0) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-ink-500">Customer payment due</dt>
                            <dd class="text-sm text-ink-800 mt-0.5">{{ $booking->customer_payment_due?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-ink-500">Vendor payment due</dt>
                            <dd class="text-sm text-ink-800 mt-0.5">{{ $booking->vendor_payment_due?->format('d M Y') ?? '—' }}</dd>
                        </div>
                    @endcan
                </dl>
            </div>

            @if($booking->booking_type === 'flight' && $booking->flight_data)
                <div class="mt-card mt-card-body">
                    <h3 class="font-semibold text-ink-900 mb-2">Flight details</h3>
                    <pre class="text-xs text-ink-600 whitespace-pre-wrap">{{ json_encode($booking->flight_data, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @elseif($booking->booking_type === 'hotel' && $booking->hotel_data)
                <div class="mt-card mt-card-body">
                    <h3 class="font-semibold text-ink-900 mb-2">Hotel details</h3>
                    <pre class="text-xs text-ink-600 whitespace-pre-wrap">{{ json_encode($booking->hotel_data, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @elseif($booking->booking_type === 'package' && $booking->package_data)
                <div class="mt-card mt-card-body">
                    <h3 class="font-semibold text-ink-900 mb-2">Package details</h3>
                    <pre class="text-xs text-ink-600 whitespace-pre-wrap">{{ json_encode($booking->package_data, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endif

            <div class="mt-card mt-card-body">
                <h3 class="font-semibold text-ink-900 mb-3">Passengers</h3>
                @forelse($booking->passengers as $p)
                    <div class="flex items-center gap-3 py-2 border-b border-ink-100 last:border-0">
                        <span class="text-sm font-medium text-ink-900">{{ $p->title }} {{ $p->first_name }} {{ $p->last_name }}</span>
                        @if($p->pivot->is_lead)<span class="mt-pill-amber">Lead</span>@endif
                        @if($p->passport_number)<span class="text-xs text-ink-500">Passport: {{ $p->passport_number }}</span>@endif
                    </div>
                @empty
                    <p class="text-sm text-ink-400">No passengers linked.</p>
                @endforelse
            </div>

            <div class="mt-card mt-card-body">
                <h3 class="font-semibold text-ink-900 mb-3">Documents</h3>
                @forelse($booking->documents as $doc)
                    <div class="flex items-center justify-between py-2 border-b border-ink-100 last:border-0">
                        <span class="text-sm text-ink-800">{{ ucfirst(str_replace('_', ' ', $doc->doc_type)) }} v{{ $doc->version_number }}</span>
                        <a href="{{ URL::signedRoute('files.download', ['token' => encrypt($doc->ulid)]) }}" class="text-sm text-brand-700 hover:text-brand-800 hover:underline">Download</a>
                    </div>
                @empty
                    <p class="text-sm text-ink-400">No documents.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
