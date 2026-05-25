<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Reports"
            subtitle="Sales, profit and operational dashboards."
            :breadcrumbs="[
                ['label' => 'Reports'],
            ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />

            {{-- Top KPI strip --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card
                    label="Bookings this month"
                    :value="number_format(($bookings ?? collect())->count())"
                    tone="brand"
                    icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                <x-stat-card
                    label="Sales this month"
                    :value="'₹' . number_format(collect($bookings ?? [])->sum(fn ($b) => $b->sale_amount?->toRupees() ?? 0))"
                    tone="emerald"
                    icon="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                <x-stat-card
                    label="Pending enquiries"
                    :value="number_format($pendingEnquiries ?? 0)"
                    tone="amber"
                    icon="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                <x-stat-card
                    label="Total customers"
                    :value="number_format($confirmedCustomers ?? 0)"
                    tone="violet"
                    icon="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-5a4 4 0 11-8 0 4 4 0 018 0zm6 3a3 3 0 11-6 0 3 3 0 016 0z" />
            </div>

            {{-- Report downloads --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="mt-card mt-card-body flex flex-col gap-3">
                    <h3 class="font-semibold text-ink-900">Bookings register</h3>
                    <p class="text-sm text-ink-500 flex-1">Full list of all bookings with customer and vendor details.</p>
                    <a href="{{ route('admin.reports.export.bookings') }}" class="mt-btn-primary mt-btn-sm self-start">
                        Download Excel
                    </a>
                </div>
                @if($isAdmin)
                    <div class="mt-card mt-card-body flex flex-col gap-3">
                        <h3 class="font-semibold text-ink-900">Sales &amp; profit</h3>
                        <p class="text-sm text-ink-500 flex-1">Revenue, costs, and margin analysis. Admin only.</p>
                        <a href="{{ route('admin.reports.export.salesProfit') }}" class="mt-btn-accent mt-btn-sm self-start">
                            Download Excel
                        </a>
                    </div>
                @endif
                <div class="mt-card mt-card-body flex flex-col gap-3">
                    <h3 class="font-semibold text-ink-900">Enquiry conversion</h3>
                    <p class="text-sm text-ink-500 flex-1">Enquiry to trip conversion rates.</p>
                    <span class="text-sm text-ink-400">View below ↓</span>
                </div>
            </div>

            {{-- Bookings table preview --}}
            <div class="mt-card">
                <div class="mt-card-header">
                    <h3 class="font-semibold text-ink-900">Bookings register preview</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="mt-table">
                        <thead>
                            <tr>
                                <th>Ref</th>
                                <th>Customer</th>
                                <th>Type</th>
                                <th>Vendor</th>
                                <th>Sale</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $b)
                                <tr>
                                    <td class="font-mono">{{ $b->booking_ref }}</td>
                                    <td>{{ $b->customer->name }}</td>
                                    <td>{{ ucfirst($b->booking_type) }}</td>
                                    <td>{{ $b->vendor?->name ?? '—' }}</td>
                                    <td>₹{{ number_format($b->sale_amount?->toRupees() ?? 0) }}</td>
                                    <td><x-status-pill :status="$b->status" /></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-ink-400 py-8">No bookings.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($isAdmin)
                <div class="mt-card">
                    <div class="mt-card-header">
                        <h3 class="font-semibold text-ink-900">AI extraction jobs</h3>
                        <a href="{{ route('admin.reports.ai-extraction') }}" class="text-sm text-brand-700 hover:text-brand-800 hover:underline">View all</a>
                    </div>
                    <div class="mt-card-body">
                        <p class="text-sm text-ink-500">Monitor AI document extraction usage and costs.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
