<div>
    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div class="flex items-center gap-2 w-full sm:w-96">
            <input
                type="search"
                wire:model.live.debounce.300ms="search"
                placeholder="Search name, code, email, phone or GSTIN…"
                class="block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.vendors.create') }}"
               class="inline-flex items-center gap-1 rounded-md bg-[var(--mt-accent,#0F4C81)] px-3 py-2 text-sm font-medium text-white shadow-sm hover:opacity-90">
                + Add vendor
            </a>
            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer select-none">
                <input type="checkbox" wire:model.live="showDeleted" class="rounded border-gray-300 text-indigo-600">
                Show archived
            </label>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-3 text-sm text-green-800">
            {{ session('status') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Code</th>
                    <th class="px-4 py-3">Contact</th>
                    <th class="px-4 py-3">Email / Phone</th>
                    <th class="px-4 py-3">Payment terms</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($this->vendors as $vendor)
                    <tr @class(['opacity-50' => $vendor->trashed()])>
                        <td class="px-4 py-3 font-medium text-gray-900">
                            <a href="{{ route('admin.vendors.show', $vendor->ulid) }}" class="hover:underline">
                                {{ $vendor->name }}
                            </a>
                            @if ($vendor->trashed())
                                <span class="ml-1 text-xs text-red-500">(archived)</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-mono text-gray-600">{{ $vendor->code ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $vendor->contact_person ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $vendor->email ?? '' }}
                            @if ($vendor->email && $vendor->phone) <br> @endif
                            {{ $vendor->phone ?? '' }}
                            @if (!$vendor->email && !$vendor->phone) — @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $vendor->payment_terms_days === 0 ? 'Immediate' : $vendor->payment_terms_days . ' days' }}
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.vendors.edit', $vendor->ulid) }}"
                               class="text-indigo-600 hover:underline text-xs">Edit</a>

                            @if ($vendor->trashed())
                                <button wire:click="restoreVendor({{ $vendor->id }})"
                                        wire:confirm="Restore {{ $vendor->name }}?"
                                        class="text-green-600 hover:underline text-xs">Restore</button>
                            @else
                                <button wire:click="deleteVendor({{ $vendor->id }})"
                                        wire:confirm="Archive {{ $vendor->name }}? The record is preserved and can be restored."
                                        class="text-red-500 hover:underline text-xs">Archive</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                            @if ($search !== '')
                                No vendors match "{{ $search }}".
                            @else
                                No vendors yet. <a href="{{ route('admin.vendors.create') }}" class="underline">Add one.</a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $this->vendors->links() }}</div>
</div>
