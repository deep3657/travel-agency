<div>
    <div class="mt-card">
        <div class="mt-card-header flex-wrap gap-3">
            <div class="flex items-center gap-2 w-full sm:w-96">
                <input
                    type="search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search name, phone, email or GSTIN…"
                    class="mt-input" />
            </div>

            @if (auth()->user()->isAdmin())
                <label class="flex items-center gap-2 text-sm text-ink-600 cursor-pointer select-none">
                    <input type="checkbox" wire:model.live="showDeleted" class="rounded border-ink-300 text-brand-700 focus:ring-brand-500">
                    Show archived
                </label>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="mt-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>GSTIN</th>
                        <th>City</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->customers as $customer)
                        <tr @class(['opacity-50' => $customer->trashed()])>
                            <td class="font-medium text-ink-900">
                                <a href="{{ route('admin.customers.show', $customer->ulid) }}"
                                   class="hover:underline text-ink-900">
                                    {{ $customer->name }}
                                </a>
                                @if ($customer->trashed())
                                    <span class="ml-1 text-xs text-red-500">(archived)</span>
                                @endif
                            </td>
                            <td>{{ $customer->phone }}</td>
                            <td>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span>{{ $customer->email }}</span>
                                    @if ($customer->user)
                                        @if ($customer->user->hasVerifiedEmail())
                                            <span class="mt-pill-green text-[10px]">Verified</span>
                                        @else
                                            <span class="mt-pill-amber text-[10px]">Unverified</span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td class="font-mono">{{ $customer->gstin ?? '—' }}</td>
                            <td>{{ $customer->city ?? '—' }}</td>
                            <td class="text-right space-x-3">
                                @can('update', $customer)
                                    <a href="{{ route('admin.customers.edit', $customer->ulid) }}"
                                       class="text-brand-700 hover:text-brand-800 hover:underline text-xs">Edit</a>
                                @endcan

                                @if ($customer->trashed())
                                    @can('restore', $customer)
                                        <button wire:click="restoreCustomer({{ $customer->id }})"
                                                wire:confirm="Restore {{ $customer->name }}?"
                                                class="text-emerald-600 hover:text-emerald-700 hover:underline text-xs">
                                            Restore
                                        </button>
                                    @endcan
                                @else
                                    @can('delete', $customer)
                                        <button wire:click="deleteCustomer({{ $customer->id }})"
                                                wire:confirm="Archive {{ $customer->name }}? The record is preserved and can be restored."
                                                class="text-red-500 hover:text-red-600 hover:underline text-xs">
                                            Archive
                                        </button>
                                    @endcan
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-ink-400 py-8">
                                @if ($search !== '')
                                    No customers match "{{ $search }}".
                                @else
                                    No customers yet.
                                    @can('create', \App\Models\Customer::class)
                                        <a href="{{ route('admin.customers.create') }}" class="underline text-brand-700">Add one.</a>
                                    @endcan
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-ink-200/70">
            {{ $this->customers->links() }}
        </div>
    </div>
</div>
