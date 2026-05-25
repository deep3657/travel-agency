<div class="space-y-6">
    <x-flash />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main column --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Key facts --}}
            <div class="mt-card mt-card-body">
                <h3 class="text-xs font-semibold text-ink-500 uppercase tracking-wide mb-4">Key facts</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-ink-500">Customer</dt>
                        <dd class="text-sm text-ink-900 font-medium">{{ $enquiry->customer->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-ink-500">Type</dt>
                        <dd class="text-sm text-ink-800">{{ ucfirst($enquiry->enquiry_type) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-ink-500">Destination</dt>
                        <dd class="text-sm text-ink-800">{{ $enquiry->destination ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-ink-500">Travel dates</dt>
                        <dd class="text-sm text-ink-800">{{ $enquiry->travel_from?->format('d M') }} – {{ $enquiry->travel_to?->format('d M Y') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-ink-500">PAX</dt>
                        <dd class="text-sm text-ink-800">{{ $enquiry->pax_adult }}A {{ $enquiry->pax_child }}C</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-ink-500">Budget</dt>
                        <dd class="text-sm text-ink-800">₹{{ number_format($enquiry->budget_min?->toRupees() ?? 0) }} – ₹{{ number_format($enquiry->budget_max?->toRupees() ?? 0) }}</dd>
                    </div>
                </dl>
                @if($enquiry->special_requirements)
                    <div class="mt-5 pt-5 border-t border-ink-200/70">
                        <dt class="text-xs uppercase tracking-wide text-ink-500 mb-1">Special requirements</dt>
                        <dd class="text-sm text-ink-800">{{ $enquiry->special_requirements }}</dd>
                    </div>
                @endif
            </div>

            {{-- Notes --}}
            <div class="mt-card mt-card-body">
                <h3 class="font-semibold text-ink-900 mb-4">Notes ({{ $enquiry->notes->count() }})</h3>
                <div class="space-y-3 mb-4">
                    @forelse($enquiry->notes as $note)
                        <div class="bg-ink-50 rounded-lg p-3 border border-ink-100">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-ink-800">{{ $note->author?->name ?? 'System' }}</span>
                                <span class="text-xs text-ink-400">{{ $note->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-ink-700">{{ $note->body }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-ink-400">No notes yet.</p>
                    @endforelse
                </div>
                <div class="flex gap-2">
                    <textarea wire:model="newNote" rows="2" placeholder="Add a note..." class="mt-textarea flex-1"></textarea>
                    <button wire:click="addNote" class="mt-btn-primary self-end">Add</button>
                </div>
                @error('newNote')<span class="mt-error">{{ $message }}</span>@enderror
            </div>
        </div>

        {{-- Side column --}}
        <div class="space-y-6">
            {{-- Conversion / next-step card --}}
            @if ($enquiry->converted_to_trip_id && $enquiry->trip)
                <div class="mt-card mt-card-body bg-gradient-to-br from-brand-50 to-white border-brand-200/70">
                    <div class="flex items-start gap-3">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-brand-600 text-white shrink-0">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-ink-900 text-sm">Converted to trip</h3>
                            <p class="text-xs text-ink-500 mt-0.5 truncate">{{ $enquiry->trip->name }}</p>
                            <a href="{{ route('admin.trips.show', $enquiry->trip->ulid) }}" class="inline-flex items-center gap-1 mt-3 text-sm font-medium text-brand-700 hover:text-brand-800">
                                Open trip
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="mt-card mt-card-body bg-gradient-to-br from-brand-700 to-brand-900 text-white border-transparent">
                    <h3 class="font-semibold text-sm">Ready to plan?</h3>
                    <p class="text-xs text-brand-100 mt-1 max-w-xs">Convert this enquiry into a trip to start building the itinerary and quotation.</p>
                    <button type="button"
                            wire:click="convertToTrip"
                            wire:loading.attr="disabled"
                            wire:target="convertToTrip"
                            class="mt-4 inline-flex items-center gap-2 rounded-lg bg-white text-brand-800 hover:bg-brand-50 px-3.5 py-2 text-sm font-semibold shadow-sm transition active:scale-[0.98] disabled:opacity-60 disabled:cursor-wait">
                        <span wire:loading.remove wire:target="convertToTrip" class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                            Convert to trip
                        </span>
                        <span wire:loading wire:target="convertToTrip" class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                            Creating…
                        </span>
                    </button>
                </div>
            @endif

            {{-- Status card --}}
            <div class="mt-card mt-card-body">
                <h3 class="text-xs font-semibold text-ink-500 uppercase tracking-wide mb-4">Status</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-ink-500">Current</span>
                        <x-status-pill :status="$enquiry->status" />
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-ink-500">Assigned to</span>
                        <span class="text-sm text-ink-800">{{ $enquiry->assignedUser?->name ?? 'Unassigned' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-ink-500">Created</span>
                        <span class="text-sm text-ink-800">{{ $enquiry->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>

            {{-- Change status --}}
            <div class="mt-card mt-card-body">
                <h3 class="text-xs font-semibold text-ink-500 uppercase tracking-wide mb-3">Change status</h3>
                <div class="flex gap-2">
                    <select wire:model="newStatus" class="mt-select flex-1">
                        <option value="new">New</option>
                        <option value="in_progress">In progress</option>
                        <option value="quoted">Quoted</option>
                        <option value="closed">Closed</option>
                    </select>
                    <button wire:click="changeStatus" class="mt-btn-primary mt-btn-sm">Update</button>
                </div>
            </div>

            {{-- Assign agent --}}
            <div class="mt-card mt-card-body">
                <h3 class="text-xs font-semibold text-ink-500 uppercase tracking-wide mb-3">Assign agent</h3>
                <div class="flex gap-2">
                    <select wire:model="assignUserId" class="mt-select flex-1">
                        <option value="">Unassigned</option>
                        @foreach($this->agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                        @endforeach
                    </select>
                    <button wire:click="assignAgent" class="mt-btn-secondary mt-btn-sm">Assign</button>
                </div>
            </div>
        </div>
    </div>
</div>
