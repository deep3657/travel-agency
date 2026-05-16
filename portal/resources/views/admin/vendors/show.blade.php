<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('admin.vendors.index') }}" class="hover:text-gray-700">Vendors</a>
                <span>/</span>
                <span class="font-semibold text-xl text-gray-800">{{ $vendor->name }}</span>
            </div>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-5">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    @foreach ([
                        'Code' => $vendor->code,
                        'Contact person' => $vendor->contact_person,
                        'Email' => $vendor->email,
                        'Phone' => $vendor->phone,
                        'GSTIN' => $vendor->gstin,
                        'Payment terms' => $vendor->payment_terms_days === 0 ? 'Immediate' : $vendor->payment_terms_days . ' days',
                    ] as $label => $value)
                        <div>
                            <dt class="text-gray-500">{{ $label }}</dt>
                            <dd class="text-gray-900 font-medium mt-0.5">{{ $value ?? '—' }}</dd>
                        </div>
                    @endforeach

                    @if ($vendor->address)
                        <div class="md:col-span-2">
                            <dt class="text-gray-500">Address</dt>
                            <dd class="text-gray-900 mt-0.5 whitespace-pre-line">{{ $vendor->address }}</dd>
                        </div>
                    @endif

                    @if ($vendor->notes)
                        <div class="md:col-span-2">
                            <dt class="text-gray-500">Notes</dt>
                            <dd class="text-gray-900 mt-0.5 whitespace-pre-wrap">{{ $vendor->notes }}</dd>
                        </div>
                    @endif
                </dl>

                <div class="flex items-center gap-4 pt-2 border-t border-gray-100">
                    <a href="{{ route('admin.vendors.edit', $vendor->ulid) }}"
                       class="inline-flex items-center rounded-md bg-[var(--mt-accent,#0F4C81)] px-4 py-2 text-sm font-medium text-white shadow-sm hover:opacity-90">
                        Edit vendor
                    </a>
                    <a href="{{ route('admin.vendors.index') }}" class="text-sm text-gray-500 hover:underline">
                        ← Back to list
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
