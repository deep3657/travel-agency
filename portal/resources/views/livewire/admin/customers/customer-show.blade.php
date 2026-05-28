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
                            <dd class="text-sm text-ink-800 break-all flex items-center gap-2 flex-wrap">
                                <span>{{ $customer->email }}</span>
                                @if ($customer->user)
                                    @if ($customer->user->hasVerifiedEmail())
                                        <span class="mt-pill-green text-[10px]">Verified</span>
                                    @else
                                        <span class="mt-pill-amber text-[10px]">Unverified</span>
                                    @endif
                                @else
                                    <span class="text-[10px] text-ink-400">No portal account</span>
                                @endif
                            </dd>
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

                    @if (session('verify_status'))
                        <div class="mt-alert-success mb-3 text-xs">
                            <svg class="h-4 w-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ session('verify_status') }}</span>
                        </div>
                    @endif
                    @if (session('verify_error'))
                        <div class="mt-alert-error mb-3 text-xs">
                            <svg class="h-4 w-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/></svg>
                            <span>{{ session('verify_error') }}</span>
                        </div>
                    @endif

                    <div class="flex flex-col gap-2">
                        @can('update', $customer)
                            <a href="{{ route('admin.customers.edit', $customer->ulid) }}" class="mt-btn-primary mt-btn-sm">
                                Edit customer
                            </a>
                            @if ($customer->user && ! $customer->user->hasVerifiedEmail())
                                <button type="button"
                                        wire:click="markEmailVerified"
                                        wire:confirm="Mark this customer's email as verified? Use only when email delivery fails or for testing."
                                        class="mt-btn-ghost mt-btn-sm">
                                    Mark email verified
                                </button>
                            @endif
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
