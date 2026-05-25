<x-app-layout>
    <x-slot name="header">
        <x-page-header
            :title="$vendor->name"
            :breadcrumbs="[
                ['label' => 'Vendors', 'href' => route('admin.vendors.index')],
                ['label' => $vendor->name],
            ]">
            <a href="{{ route('admin.vendors.edit', $vendor->ulid) }}" class="mt-btn-primary mt-btn-sm">
                Edit vendor
            </a>
        </x-page-header>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />

            <div class="mt-card mt-card-body space-y-5">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    @foreach ([
                        'Code' => $vendor->code,
                        'Contact person' => $vendor->contact_person,
                        'Email' => $vendor->email,
                        'Phone' => $vendor->phone,
                        'GSTIN' => $vendor->gstin,
                        'Payment terms' => $vendor->payment_terms_days === 0 ? 'Immediate' : $vendor->payment_terms_days . ' days',
                    ] as $label => $value)
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-ink-500">{{ $label }}</dt>
                            <dd class="text-ink-900 font-medium mt-0.5">{{ $value ?? '—' }}</dd>
                        </div>
                    @endforeach

                    @if ($vendor->address)
                        <div class="md:col-span-2">
                            <dt class="text-xs uppercase tracking-wide text-ink-500">Address</dt>
                            <dd class="text-ink-800 mt-0.5 whitespace-pre-line">{{ $vendor->address }}</dd>
                        </div>
                    @endif

                    @if ($vendor->notes)
                        <div class="md:col-span-2">
                            <dt class="text-xs uppercase tracking-wide text-ink-500">Notes</dt>
                            <dd class="text-ink-800 mt-0.5 whitespace-pre-wrap">{{ $vendor->notes }}</dd>
                        </div>
                    @endif
                </dl>

                <div class="flex items-center gap-4 pt-4 border-t border-ink-200/70">
                    <a href="{{ route('admin.vendors.edit', $vendor->ulid) }}" class="mt-btn-primary mt-btn-sm">
                        Edit vendor
                    </a>
                    <a href="{{ route('admin.vendors.index') }}" class="text-sm text-ink-500 hover:text-ink-700">
                        ← Back to list
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
