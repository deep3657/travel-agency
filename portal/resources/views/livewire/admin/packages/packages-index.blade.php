<div>
    <div class="mt-card">
        <div class="mt-card-header flex-wrap gap-3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search packages..." class="mt-input w-64">
            @if(auth()->user()?->isAdmin())
                <label class="flex items-center gap-2 text-sm text-ink-600 cursor-pointer select-none">
                    <input wire:model.live="showDeleted" type="checkbox" class="rounded border-ink-300 text-brand-700 focus:ring-brand-500">
                    Show archived
                </label>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="mt-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Destinations</th>
                        <th>Duration</th>
                        <th>Price from</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->packages as $pkg)
                        <tr class="{{ $pkg->trashed() ? 'opacity-50' : '' }}">
                            <td>
                                <a href="{{ route('admin.packages.show', $pkg->ulid) }}" class="font-medium text-brand-700 hover:text-brand-800 hover:underline">{{ $pkg->title }}</a>
                            </td>
                            <td>{{ $pkg->destinations }}</td>
                            <td>{{ $pkg->duration_days }}D/{{ $pkg->duration_nights }}N</td>
                            <td>₹{{ number_format($pkg->price_from_inr->toRupees()) }}</td>
                            <td><x-status-pill :status="$pkg->status" /></td>
                            <td class="text-right space-x-3">
                                @if($pkg->trashed())
                                    @can('restore', $pkg)
                                        <button wire:click="restorePackage({{ $pkg->id }})" wire:confirm="Restore this package?" class="text-emerald-600 hover:text-emerald-700 hover:underline text-xs">Restore</button>
                                    @endcan
                                @else
                                    <a href="{{ route('admin.packages.edit', $pkg->ulid) }}" class="text-brand-700 hover:text-brand-800 hover:underline text-xs">Edit</a>
                                    @can('delete', $pkg)
                                        <button wire:click="deletePackage({{ $pkg->id }})" wire:confirm="Archive this package?" class="text-red-500 hover:text-red-600 hover:underline text-xs">Archive</button>
                                    @endcan
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-ink-400 py-8">No packages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-ink-200/70">
            {{ $this->packages->links() }}
        </div>
    </div>
</div>
