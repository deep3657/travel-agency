<div class="space-y-6">
    <x-flash />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main column --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Trip overview --}}
            <div class="mt-card mt-card-body">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-ink-900">{{ $trip->name }}</h2>
                        <p class="text-sm text-ink-500 mt-1">{{ $trip->customer->name }} · {{ $trip->primary_destination ?? 'Destination TBD' }}</p>
                    </div>
                    <x-status-pill :status="$trip->status" />
                </div>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-ink-500">Travel start</dt>
                        <dd class="text-sm text-ink-800">{{ $trip->travel_start?->format('d M Y') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-ink-500">Travel end</dt>
                        <dd class="text-sm text-ink-800">{{ $trip->travel_end?->format('d M Y') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-ink-500">Assigned agent</dt>
                        <dd class="text-sm text-ink-800">{{ $trip->assignedUser?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-ink-500">Quotations</dt>
                        <dd class="text-sm text-ink-800">{{ $trip->quotations->count() }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Quotations --}}
            <div class="mt-card">
                <div class="mt-card-header">
                    <h3 class="font-semibold text-ink-900">Quotations</h3>
                    @can('create', \App\Models\Quotation::class)
                        <button wire:click="createQuotation" class="mt-btn-primary mt-btn-sm">
                            + New quotation
                        </button>
                    @endcan
                </div>
                <div class="mt-card-body space-y-3">
                    @forelse($trip->quotations as $q)
                        <div class="border border-ink-200 rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-ink-900">Quotation #{{ $q->id }}</span>
                                    <x-status-pill :status="$q->status" />
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.quotations.editor', $q->ulid) }}" class="text-brand-700 hover:text-brand-800 hover:underline text-sm">Edit</a>
                                </div>
                            </div>
                            @if($q->currentVersion)
                                <div class="mt-2 text-sm text-ink-600">
                                    Version {{ $q->currentVersion->version_number }} · Grand total: <strong class="text-ink-900">₹{{ number_format($q->currentVersion->grand_total?->toRupees() ?? 0) }}</strong>
                                    @if($q->currentVersion->sent_at) · Sent {{ $q->currentVersion->sent_at->format('d M Y') }}@endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-ink-400">No quotations yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Bookings --}}
            <div class="mt-card">
                <div class="mt-card-header">
                    <h3 class="font-semibold text-ink-900">Bookings</h3>
                    <a href="{{ route('admin.bookings.create') }}?trip_id={{ $trip->id }}" class="mt-btn-secondary mt-btn-sm">+ New booking</a>
                </div>
                <div class="mt-card-body space-y-2">
                    @forelse($trip->bookings as $b)
                        <div class="border border-ink-200 rounded-lg p-3 flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <span class="font-mono text-sm font-medium text-ink-900">{{ $b->booking_ref }}</span>
                                <span class="text-sm text-ink-500">{{ ucfirst($b->booking_type) }}</span>
                                <x-status-pill :status="$b->status" />
                            </div>
                            <a href="{{ route('admin.bookings.show', $b->ulid) }}" class="text-brand-700 hover:text-brand-800 hover:underline text-sm">View</a>
                        </div>
                    @empty
                        <p class="text-sm text-ink-400">No bookings yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Side column --}}
        <div class="space-y-6">
            <div class="mt-card mt-card-body">
                <h3 class="text-xs font-semibold text-ink-500 uppercase tracking-wide mb-4">Customer</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-ink-500">Name</dt>
                        <dd class="text-sm text-ink-900 font-medium">{{ $trip->customer->name }}</dd>
                    </div>
                    @if($trip->customer->phone)
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-ink-500">Phone</dt>
                            <dd class="text-sm text-ink-800">{{ $trip->customer->phone }}</dd>
                        </div>
                    @endif
                    @if($trip->customer->email)
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-ink-500">Email</dt>
                            <dd class="text-sm text-ink-800 break-all">{{ $trip->customer->email }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
