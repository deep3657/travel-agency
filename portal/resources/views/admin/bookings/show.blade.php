<x-app-layout>
    @php
        $booking->loadMissing([
            'customer',
            'vendor',
            'passengers',
            'documents' => fn ($q) => $q->orderByDesc('version_number')->orderByDesc('id'),
            'documents.generatedBy',
            'supplierDocuments' => fn ($q) => $q->orderByDesc('id'),
            'supplierDocuments.uploadedBy',
            'supplierDocuments.supplierVendor',
        ]);

        $vouchers = $booking->documents;
        $supplierDocs = $booking->supplierDocuments;

        $docTypeLabel = function (string $type): string {
            return ucwords(str_replace('_', ' ', $type));
        };

        $formatBytes = function (int $bytes): string {
            if ($bytes < 1024) {
                return $bytes.' B';
            }
            if ($bytes < 1024 * 1024) {
                return number_format($bytes / 1024, 1).' KB';
            }

            return number_format($bytes / (1024 * 1024), 2).' MB';
        };

        // The "current" voucher for each doc_type is the one with the highest
        // version_number. We highlight it so the admin knows at a glance which
        // file the customer is currently being shown.
        $currentVoucherIdsByType = $vouchers
            ->groupBy('doc_type')
            ->map(fn ($docs) => $docs->sortByDesc('version_number')->first()?->id)
            ->filter()
            ->values()
            ->all();
    @endphp

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
                <button class="mt-btn-primary mt-btn-sm">
                    {{ $vouchers->isEmpty() ? 'Generate voucher' : 'Regenerate voucher' }}
                </button>
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

            {{-- ============================================================
                 Maruti Travels branded vouchers (PRD §5.5, §5.7, M10)
                 Generated from the standard PDF template using booking data.
                 Versioned: each "Regenerate" creates a new version; the latest
                 version is the one customers see.
                 ============================================================ --}}
            <div class="mt-card">
                <div class="mt-card-header flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-semibold text-ink-900">Maruti Travels vouchers</h3>
                        <p class="text-xs text-ink-500 mt-0.5">
                            Standardised PDFs generated from your branded template — these are what the customer sees.
                        </p>
                    </div>
                    <form method="POST" action="{{ route('admin.vouchers.generate', $booking->ulid) }}">
                        @csrf
                        <button class="mt-btn-secondary mt-btn-sm">
                            {{ $vouchers->isEmpty() ? '+ Generate voucher' : '↻ Regenerate' }}
                        </button>
                    </form>
                </div>
                <div class="p-5">
                    @forelse($vouchers as $doc)
                        @php($isCurrent = in_array($doc->id, $currentVoucherIdsByType, true))
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 py-3 border-b border-ink-100 last:border-0">
                            <div class="flex items-start gap-3">
                                <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </span>
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-sm font-medium text-ink-900">{{ $docTypeLabel($doc->doc_type) }}</span>
                                        <span class="text-xs font-mono text-ink-500">v{{ $doc->version_number }}</span>
                                        @if($isCurrent)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-semibold uppercase tracking-wide">Current</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-ink-100 text-ink-500 text-[10px] font-semibold uppercase tracking-wide">Archived</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-ink-500 mt-1">
                                        {{ $formatBytes((int) $doc->size_bytes) }}
                                        · Generated {{ $doc->generated_at?->format('d M Y, H:i') ?? '—' }}
                                        @if($doc->generatedBy) by {{ $doc->generatedBy->name }} @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                                <a href="{{ URL::signedRoute('files.download', ['token' => $doc->ulid]) }}"
                                   class="mt-btn-secondary mt-btn-sm">
                                    Download
                                </a>
                                @if($isCurrent)
                                    <form method="POST" action="{{ route('admin.vouchers.email', [$booking->ulid, $doc->ulid]) }}">
                                        @csrf
                                        <button class="mt-btn-secondary mt-btn-sm">Email to customer</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <x-empty-state
                            title="No standardised voucher yet"
                            description="Create a Maruti Travels branded {{ $booking->booking_type }} voucher PDF from your standard template. The customer will see this version, not the supplier's original.">
                            <form method="POST" action="{{ route('admin.vouchers.generate', $booking->ulid) }}">
                                @csrf
                                <button class="mt-btn-primary">
                                    Generate {{ ucfirst($booking->booking_type) }} Voucher
                                </button>
                            </form>
                        </x-empty-state>
                    @endforelse
                </div>
            </div>

            {{-- ============================================================
                 Original supplier documents (PRD §5.6, M11)
                 The supplier-issued PDF (Tripjack / TBO / airline / hotel
                 portal etc.) kept for audit and reference. Never shown to
                 the customer — internal staff only.
                 ============================================================ --}}
            <div class="mt-card">
                <div class="mt-card-header flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-semibold text-ink-900">Original supplier documents</h3>
                        <p class="text-xs text-ink-500 mt-0.5">
                            Internal reference only — the original PDFs we received from the supplier. Not shared with the customer.
                        </p>
                    </div>
                    <a href="{{ route('admin.supplier-docs.new', ['booking' => $booking->ulid]) }}"
                       class="mt-btn-secondary mt-btn-sm">
                        + Upload supplier doc
                    </a>
                </div>
                <div class="p-5">
                    @forelse($supplierDocs as $sd)
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 py-3 border-b border-ink-100 last:border-0">
                            <div class="flex items-start gap-3">
                                <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
                                </span>
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-sm font-medium text-ink-900">{{ $sd->original_filename }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-ink-100 text-ink-600 text-[10px] font-semibold uppercase tracking-wide">{{ $docTypeLabel($sd->doc_type) }}</span>
                                        @if($sd->extraction_mode === 'ai')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-violet-50 text-violet-700 text-[10px] font-semibold uppercase tracking-wide">AI-extracted</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-ink-500 mt-1">
                                        {{ $formatBytes((int) $sd->size_bytes) }}
                                        @if($sd->supplierVendor)
                                            · Supplier: {{ $sd->supplierVendor->name }}
                                        @elseif($sd->supplier_name)
                                            · Supplier: {{ $sd->supplier_name }}
                                        @endif
                                        · Uploaded {{ $sd->created_at?->format('d M Y, H:i') ?? '—' }}
                                        @if($sd->uploadedBy) by {{ $sd->uploadedBy->name }} @endif
                                    </p>
                                </div>
                            </div>
                            <a href="{{ URL::signedRoute('files.download', ['token' => $sd->ulid]) }}"
                               class="mt-btn-secondary mt-btn-sm">
                                Download original
                            </a>
                        </div>
                    @empty
                        <x-empty-state
                            title="No supplier documents uploaded"
                            description="Upload the supplier-issued PDF (Tripjack, TBO, airline / hotel portal, etc.) for record-keeping." />
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
