<div>
    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-4 border-b flex flex-wrap gap-3 items-center">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search packages..."
                class="border border-gray-300 rounded-md px-3 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @if(auth()->user()?->isAdmin())
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input wire:model.live="showDeleted" type="checkbox" class="rounded">
                    Show archived
                </label>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destinations</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price From</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($this->packages as $pkg)
                        <tr class="{{ $pkg->trashed() ? 'opacity-50' : '' }}">
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.packages.show', $pkg->ulid) }}" class="font-medium text-blue-700 hover:underline">{{ $pkg->title }}</a>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $pkg->destinations }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $pkg->duration_days }}D/{{ $pkg->duration_nights }}N</td>
                            <td class="px-4 py-3 text-sm">₹{{ number_format($pkg->price_from_inr->toRupees()) }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium {{ $pkg->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($pkg->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right text-sm">
                                @if($pkg->trashed())
                                    @can('restore', $pkg)
                                        <button wire:click="restorePackage({{ $pkg->id }})" wire:confirm="Restore this package?" class="text-green-600 hover:text-green-800">Restore</button>
                                    @endcan
                                @else
                                    <a href="{{ route('admin.packages.edit', $pkg->ulid) }}" class="text-gray-600 hover:text-gray-900 mr-3">Edit</a>
                                    @can('delete', $pkg)
                                        <button wire:click="deletePackage({{ $pkg->id }})" wire:confirm="Archive this package?" class="text-red-500 hover:text-red-700">Archive</button>
                                    @endcan
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">No packages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t">
            {{ $this->packages->links() }}
        </div>
    </div>
</div>
