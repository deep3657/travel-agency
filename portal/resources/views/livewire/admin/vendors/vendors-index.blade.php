<div>
    <div class="mt-card">
        <div class="mt-card-header flex-wrap gap-3">
            <div class="flex items-center gap-2 w-full sm:w-96">
                <input
                    type="search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search name, code, email, phone or GSTIN…"
                    class="mt-input" />
            </div>
            <label class="flex items-center gap-2 text-sm text-ink-600 cursor-pointer select-none">
                <input type="checkbox" wire:model.live="showDeleted" class="rounded border-ink-300 text-brand-700 focus:ring-brand-500">
                Show archived
            </label>
        </div>

        <div class="overflow-x-auto">
            <table class="mt-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Contact</th>
                        <th>Email / Phone</th>
                        <th>Payment terms</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->vendors as $vendor)
                        <tr @class(['opacity-50' => $vendor->trashed()])>
                            <td class="font-medium text-ink-900">
                                <a href="{{ route('admin.vendors.show', $vendor->ulid) }}" class="hover:underline text-ink-900">
                                    {{ $vendor->name }}
                                </a>
                                @if ($vendor->trashed())
                                    <span class="ml-1 text-xs text-red-500">(archived)</span>
                                @endif
                            </td>
                            <td class="font-mono">{{ $vendor->code ?? '—' }}</td>
                            <td>{{ $vendor->contact_person ?? '—' }}</td>
                            <td>
                                {{ $vendor->email ?? '' }}
                                @if ($vendor->email && $vendor->phone) <br> @endif
                                {{ $vendor->phone ?? '' }}
                                @if (!$vendor->email && !$vendor->phone) — @endif
                            </td>
                            <td>{{ $vendor->payment_terms_days === 0 ? 'Immediate' : $vendor->payment_terms_days . ' days' }}</td>
                            <td class="text-right space-x-3">
                                <a href="{{ route('admin.vendors.edit', $vendor->ulid) }}"
                                   class="text-brand-700 hover:text-brand-800 hover:underline text-xs">Edit</a>

                                @if ($vendor->trashed())
                                    <button wire:click="restoreVendor({{ $vendor->id }})"
                                            wire:confirm="Restore {{ $vendor->name }}?"
                                            class="text-emerald-600 hover:text-emerald-700 hover:underline text-xs">Restore</button>
                                @else
                                    <button wire:click="deleteVendor({{ $vendor->id }})"
                                            wire:confirm="Archive {{ $vendor->name }}? The record is preserved and can be restored."
                                            class="text-red-500 hover:text-red-600 hover:underline text-xs">Archive</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-ink-400 py-8">
                                @if ($search !== '')
                                    No vendors match "{{ $search }}".
                                @else
                                    No vendors yet. <a href="{{ route('admin.vendors.create') }}" class="underline text-brand-700">Add one.</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-ink-200/70">{{ $this->vendors->links() }}</div>
    </div>
</div>
