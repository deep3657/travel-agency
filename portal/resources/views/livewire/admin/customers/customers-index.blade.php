<div>
    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div class="flex items-center gap-2 w-full sm:w-96">
            <input
                type="search"
                wire:model.live.debounce.300ms="search"
                placeholder="Search name, phone, email or GSTIN…"
                class="block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
        </div>

        <div class="flex items-center gap-4">
            @can('create', \App\Models\Customer::class)
                <a href="{{ route('admin.customers.create') }}"
                   class="inline-flex items-center gap-1 rounded-md bg-[var(--mt-accent,#0F4C81)] px-3 py-2 text-sm font-medium text-white shadow-sm hover:opacity-90">
                    + Add customer
                </a>
            @endcan

            @if (auth()->user()->isAdmin())
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer select-none">
                    <input type="checkbox" wire:model.live="showDeleted" class="rounded border-gray-300 text-indigo-600">
                    Show archived
                </label>
            @endif
        </div>
    </div>

    {{-- Flash --}}
    @if (session('status'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-3 text-sm text-green-800">
            {{ session('status') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Phone</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">GSTIN</th>
                    <th class="px-4 py-3">City</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($this->customers as $customer)
                    <tr @class(['opacity-50' => $customer->trashed()])>
                        <td class="px-4 py-3 font-medium text-gray-900">
                            <a href="{{ route('admin.customers.show', $customer->ulid) }}"
                               class="hover:underline">
                                {{ $customer->name }}
                            </a>
                            @if ($customer->trashed())
                                <span class="ml-1 text-xs text-red-500">(archived)</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $customer->phone }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $customer->email }}</td>
                        <td class="px-4 py-3 font-mono text-gray-600">{{ $customer->gstin ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $customer->city ?? '—' }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            @can('update', $customer)
                                <a href="{{ route('admin.customers.edit', $customer->ulid) }}"
                                   class="text-indigo-600 hover:underline text-xs">Edit</a>
                            @endcan

                            @if ($customer->trashed())
                                @can('restore', $customer)
                                    <button wire:click="restoreCustomer({{ $customer->id }})"
                                            wire:confirm="Restore {{ $customer->name }}?"
                                            class="text-green-600 hover:underline text-xs">
                                        Restore
                                    </button>
                                @endcan
                            @else
                                @can('delete', $customer)
                                    <button wire:click="deleteCustomer({{ $customer->id }})"
                                            wire:confirm="Archive {{ $customer->name }}? The record is preserved and can be restored."
                                            class="text-red-500 hover:underline text-xs">
                                        Archive
                                    </button>
                                @endcan
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                            @if ($search !== '')
                                No customers match "{{ $search }}".
                            @else
                                No customers yet. <a href="{{ route('admin.customers.create') }}" class="underline">Add one.</a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $this->customers->links() }}
    </div>
</div>
