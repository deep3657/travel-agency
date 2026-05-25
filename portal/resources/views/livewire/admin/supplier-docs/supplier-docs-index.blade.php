<div>
    @php
        $formatBytes = function (int $bytes): string {
            if ($bytes < 1024) {
                return $bytes.' B';
            }
            if ($bytes < 1024 * 1024) {
                return number_format($bytes / 1024, 1).' KB';
            }

            return number_format($bytes / (1024 * 1024), 2).' MB';
        };
    @endphp

    <div class="mt-card">
        <div class="mt-card-header flex-wrap gap-3">
            <input wire:model.live.debounce.300ms="search" type="text"
                   placeholder="Search filename, supplier, booking ref, customer…"
                   class="mt-input w-full sm:w-96">
            <select wire:model.live="filterType" class="mt-select w-auto">
                <option value="all">All types</option>
                <option value="flight">Flight</option>
                <option value="hotel">Hotel</option>
                <option value="package">Package</option>
                <option value="other">Other</option>
            </select>
            <select wire:model.live="filterAttached" class="mt-select w-auto">
                <option value="all">All uploads</option>
                <option value="attached">Linked to a booking</option>
                <option value="orphan">Not yet linked</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="mt-table">
                <thead>
                    <tr>
                        <th>File</th>
                        <th>Type</th>
                        <th>Supplier</th>
                        <th>Booking</th>
                        <th>Uploaded</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->documents as $sd)
                        <tr>
                            <td>
                                <div class="text-sm font-medium text-ink-900">{{ $sd->original_filename }}</div>
                                <div class="text-xs text-ink-500 mt-0.5">
                                    {{ $formatBytes((int) $sd->size_bytes) }}
                                    @if($sd->extraction_mode === 'ai')
                                        · <span class="text-violet-700">AI-extracted</span>
                                    @endif
                                </div>
                            </td>
                            <td class="capitalize text-sm text-ink-700">{{ $sd->doc_type }}</td>
                            <td class="text-sm text-ink-700">
                                {{ $sd->supplierVendor?->name ?? $sd->supplier_name ?? '—' }}
                            </td>
                            <td class="text-sm">
                                @if($sd->booking)
                                    <a href="{{ route('admin.bookings.show', $sd->booking->ulid) }}"
                                       class="font-mono font-medium text-brand-700 hover:text-brand-800 hover:underline">
                                        {{ $sd->booking->booking_ref }}
                                    </a>
                                    @if($sd->booking->customer)
                                        <div class="text-xs text-ink-500 mt-0.5">{{ $sd->booking->customer->name }}</div>
                                    @endif
                                @else
                                    <span class="text-xs text-amber-700">Not linked</span>
                                @endif
                            </td>
                            <td class="text-sm text-ink-600">
                                <div>{{ $sd->created_at?->format('d M Y') ?? '—' }}</div>
                                @if($sd->uploadedBy)
                                    <div class="text-xs text-ink-500 mt-0.5">by {{ $sd->uploadedBy->name }}</div>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ URL::signedRoute('files.download', ['token' => $sd->ulid]) }}"
                                   class="text-brand-700 hover:text-brand-800 hover:underline text-sm">
                                    Download
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-ink-400 py-10">
                                No supplier documents uploaded yet. Use the “Upload document” button to add one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-ink-200/70">
            {{ $this->documents->links() }}
        </div>
    </div>
</div>
