<div>
    @php
        $tabs = [
            'overview'  => ['Overview',   true,  null],
            'enquiries' => ['Enquiries',  false, 'M6'],
            'trips'     => ['Trips',      false, 'M7'],
            'bookings'  => ['Bookings',   false, 'M9'],
            'documents' => ['Documents',  false, 'M11'],
        ];
    @endphp

    {{-- Tab bar --}}
    <div class="border-b border-ink-200/70 mb-6">
        <nav class="-mb-px flex gap-6" aria-label="Customer tabs">
            @foreach ($tabs as $key => [$label, $enabled, $milestone])
                @if ($enabled)
                    <button wire:click="$set('activeTab','{{ $key }}')"
                            @class([
                                'whitespace-nowrap py-3 px-1 border-b-2 text-sm font-medium',
                                'border-brand-700 text-brand-700' => $activeTab === $key,
                                'border-transparent text-ink-500 hover:text-ink-700' => $activeTab !== $key,
                            ])>{{ $label }}</button>
                @else
                    <span class="whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-sm font-medium text-ink-300 cursor-not-allowed"
                          title="Coming in {{ $milestone }}">{{ $label }}</span>
                @endif
            @endforeach
        </nav>
    </div>

    {{-- Overview tab --}}
    @if ($activeTab === 'overview')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main column --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Contact --}}
                <div class="mt-card mt-card-body">
                    <h3 class="text-xs font-semibold text-ink-500 uppercase tracking-wide mb-4">Contact</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-ink-500">Phone</dt>
                            <dd class="text-sm text-ink-800">{{ $customer->phone }}</dd>
                        </div>
                        @if ($customer->alt_phone)
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-ink-500">Alt phone</dt>
                                <dd class="text-sm text-ink-800">{{ $customer->alt_phone }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-ink-500">Email</dt>
                            <dd class="text-sm text-ink-800 break-all">{{ $customer->email }}</dd>
                        </div>
                        @if ($customer->dob)
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-ink-500">DOB</dt>
                                <dd class="text-sm text-ink-800">{{ $customer->dob->format('d M Y') }}</dd>
                            </div>
                        @endif
                        @if ($customer->anniversary)
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-ink-500">Anniversary</dt>
                                <dd class="text-sm text-ink-800">{{ $customer->anniversary->format('d M Y') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Address --}}
                <div class="mt-card mt-card-body">
                    <h3 class="text-xs font-semibold text-ink-500 uppercase tracking-wide mb-3">Address</h3>
                    <p class="text-sm text-ink-700 leading-relaxed whitespace-pre-line">
                        {{ collect([$customer->address_line1, $customer->address_line2, $customer->city, $customer->state, $customer->pincode, $customer->country])->filter()->implode(', ') ?: '—' }}
                    </p>
                </div>

                {{-- Notes --}}
                @if ($customer->notes)
                    <div class="mt-card mt-card-body">
                        <h3 class="text-xs font-semibold text-ink-500 uppercase tracking-wide mb-3">Internal notes</h3>
                        <p class="text-sm text-ink-700 whitespace-pre-wrap">{{ $customer->notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Side column --}}
            <div class="space-y-6">
                {{-- GST / Company --}}
                @if ($customer->gstin || $customer->pan || $customer->company_name)
                    <div class="mt-card mt-card-body">
                        <h3 class="text-xs font-semibold text-ink-500 uppercase tracking-wide mb-4">Tax / Company</h3>
                        <dl class="space-y-3">
                            @if ($customer->company_name)
                                <div>
                                    <dt class="text-xs uppercase tracking-wide text-ink-500">Company</dt>
                                    <dd class="text-sm text-ink-800">{{ $customer->company_name }}</dd>
                                </div>
                            @endif
                            @if ($customer->gstin)
                                <div>
                                    <dt class="text-xs uppercase tracking-wide text-ink-500">GSTIN</dt>
                                    <dd class="font-mono text-sm text-ink-800">{{ $customer->gstin }}</dd>
                                </div>
                            @endif
                            @if ($customer->pan)
                                <div>
                                    <dt class="text-xs uppercase tracking-wide text-ink-500">PAN</dt>
                                    <dd class="font-mono text-sm text-ink-800">{{ $customer->pan }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                @endif

                {{-- Actions card --}}
                <div class="mt-card mt-card-body">
                    <h3 class="text-xs font-semibold text-ink-500 uppercase tracking-wide mb-4">Actions</h3>
                    <div class="flex flex-col gap-2">
                        @can('update', $customer)
                            <a href="{{ route('admin.customers.edit', $customer->ulid) }}" class="mt-btn-primary mt-btn-sm">
                                Edit customer
                            </a>
                        @endcan
                        <a href="{{ route('admin.customers.index') }}" class="mt-btn-ghost mt-btn-sm">
                            ← Back to list
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
