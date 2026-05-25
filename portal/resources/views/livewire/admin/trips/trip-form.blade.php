<form wire:submit.prevent="save" class="space-y-6">
    @if (session('error'))
        <div class="mt-alert-error"><span>{{ session('error') }}</span></div>
    @endif

    {{-- Customer --}}
    <fieldset class="mt-card mt-card-body space-y-4">
        <div>
            <h3 class="text-sm font-semibold text-ink-900">Customer</h3>
            <p class="text-xs text-ink-500 mt-0.5">Search by name, phone or email. Need a new customer?
                <a href="{{ route('admin.customers.create') }}" class="text-brand-700 hover:underline">Add one first</a>.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label class="mt-label">Find customer</label>
                <input type="search"
                       wire:model.live.debounce.300ms="customerSearch"
                       placeholder="Search…"
                       class="mt-input"
                       autocomplete="off" />
            </div>
            <div class="md:col-span-2">
                <label class="mt-label">Customer <span class="text-red-500">*</span></label>
                <select wire:model.live="customer_id" class="mt-select w-full">
                    <option value="">— Select a customer —</option>
                    @foreach($this->customerOptions as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} · {{ $c->phone }}@if($c->email) · {{ $c->email }}@endif</option>
                    @endforeach
                </select>
                @error('customer_id') <p class="mt-error">{{ $message }}</p> @enderror
                @if ($customerSearch !== '' && $this->customerOptions->isEmpty())
                    <p class="text-xs text-ink-400 mt-1">No customers match "{{ $customerSearch }}".</p>
                @endif
            </div>
        </div>
    </fieldset>

    {{-- Trip basics --}}
    <fieldset class="mt-card mt-card-body space-y-4">
        <h3 class="text-sm font-semibold text-ink-900">Trip basics</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="mt-label">Trip name <span class="text-red-500">*</span></label>
                <input type="text" wire:model="name" class="mt-input" placeholder="e.g. Sharma family · Bali · Dec 2026" />
                @error('name') <p class="mt-error">{{ $message }}</p> @enderror
                <p class="text-xs text-ink-400 mt-1">We auto-suggest a name from customer, destination and dates — feel free to override.</p>
            </div>

            <div>
                <label class="mt-label">Primary destination</label>
                <input type="text" wire:model.live.debounce.500ms="primary_destination" class="mt-input" placeholder="e.g. Bali, Indonesia" />
                @error('primary_destination') <p class="mt-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mt-label">Status</label>
                <select wire:model="status" class="mt-select w-full">
                    <option value="planning">Planning</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                @error('status') <p class="mt-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mt-label">Travel start</label>
                <input type="date" wire:model.live="travel_start" class="mt-input" />
                @error('travel_start') <p class="mt-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mt-label">Travel end</label>
                <input type="date" wire:model="travel_end" class="mt-input" />
                @error('travel_end') <p class="mt-error">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="mt-label">Assigned agent</label>
                <select wire:model="assigned_user_id" class="mt-select w-full">
                    <option value="">Unassigned</option>
                    @foreach($this->agents as $a)
                        <option value="{{ $a->id }}">{{ $a->name }}</option>
                    @endforeach
                </select>
                @error('assigned_user_id') <p class="mt-error">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="mt-label">Internal notes</label>
                <textarea wire:model="notes" rows="3" class="mt-textarea" placeholder="Anything the team should know about this trip…"></textarea>
                @error('notes') <p class="mt-error">{{ $message }}</p> @enderror
            </div>
        </div>
    </fieldset>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.trips.index') }}" class="mt-btn-secondary mt-btn-sm">Cancel</a>
        <button type="submit"
                class="mt-btn-primary"
                wire:loading.attr="disabled"
                wire:target="save">
            <span wire:loading.remove wire:target="save">Create trip</span>
            <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                Creating…
            </span>
        </button>
    </div>
</form>
