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
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex gap-6" aria-label="Customer tabs">
            @foreach ($tabs as $key => [$label, $enabled, $milestone])
                @if ($enabled)
                    <button wire:click="$set('activeTab','{{ $key }}')"
                            @class([
                                'whitespace-nowrap py-3 px-1 border-b-2 text-sm font-medium',
                                'border-[var(--mt-accent,#0F4C81)] text-[var(--mt-accent,#0F4C81)]' => $activeTab === $key,
                                'border-transparent text-gray-500 hover:text-gray-700' => $activeTab !== $key,
                            ])>{{ $label }}</button>
                @else
                    <span class="whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-sm font-medium text-gray-300 cursor-not-allowed"
                          title="Coming in {{ $milestone }}">{{ $label }}</span>
                @endif
            @endforeach
        </nav>
    </div>

    {{-- Overview tab --}}
    @if ($activeTab === 'overview')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Contact --}}
            <div class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Contact</h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex gap-2">
                        <dt class="w-32 shrink-0 text-gray-500">Phone</dt>
                        <dd class="text-gray-900">{{ $customer->phone }}</dd>
                    </div>
                    @if ($customer->alt_phone)
                        <div class="flex gap-2">
                            <dt class="w-32 shrink-0 text-gray-500">Alt phone</dt>
                            <dd class="text-gray-900">{{ $customer->alt_phone }}</dd>
                        </div>
                    @endif
                    <div class="flex gap-2">
                        <dt class="w-32 shrink-0 text-gray-500">Email</dt>
                        <dd class="text-gray-900 break-all">{{ $customer->email }}</dd>
                    </div>
                    @if ($customer->dob)
                        <div class="flex gap-2">
                            <dt class="w-32 shrink-0 text-gray-500">DOB</dt>
                            <dd class="text-gray-900">{{ $customer->dob->format('d M Y') }}</dd>
                        </div>
                    @endif
                    @if ($customer->anniversary)
                        <div class="flex gap-2">
                            <dt class="w-32 shrink-0 text-gray-500">Anniversary</dt>
                            <dd class="text-gray-900">{{ $customer->anniversary->format('d M Y') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Address --}}
            <div class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Address</h3>
                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ collect([$customer->address_line1, $customer->address_line2, $customer->city, $customer->state, $customer->pincode, $customer->country])->filter()->implode(', ') ?: '—' }}
                </p>
            </div>

            {{-- GST / Company --}}
            @if ($customer->gstin || $customer->pan || $customer->company_name)
                <div class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Tax / Company</h3>
                    <dl class="space-y-2 text-sm">
                        @if ($customer->company_name)
                            <div class="flex gap-2"><dt class="w-32 shrink-0 text-gray-500">Company</dt><dd class="text-gray-900">{{ $customer->company_name }}</dd></div>
                        @endif
                        @if ($customer->gstin)
                            <div class="flex gap-2"><dt class="w-32 shrink-0 text-gray-500">GSTIN</dt><dd class="font-mono text-gray-900">{{ $customer->gstin }}</dd></div>
                        @endif
                        @if ($customer->pan)
                            <div class="flex gap-2"><dt class="w-32 shrink-0 text-gray-500">PAN</dt><dd class="font-mono text-gray-900">{{ $customer->pan }}</dd></div>
                        @endif
                    </dl>
                </div>
            @endif

            {{-- Notes --}}
            @if ($customer->notes)
                <div class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Internal notes</h3>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $customer->notes }}</p>
                </div>
            @endif
        </div>

        {{-- Actions bar --}}
        <div class="mt-6 flex items-center gap-4">
            @can('update', $customer)
                <a href="{{ route('admin.customers.edit', $customer->ulid) }}"
                   class="inline-flex items-center rounded-md bg-[var(--mt-accent,#0F4C81)] px-4 py-2 text-sm font-medium text-white shadow-sm hover:opacity-90">
                    Edit customer
                </a>
            @endcan
            <a href="{{ route('admin.customers.index') }}"
               class="text-sm text-gray-500 hover:underline">
                ← Back to list
            </a>
        </div>
    @endif
</div>
