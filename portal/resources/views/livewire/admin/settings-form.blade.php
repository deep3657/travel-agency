<div>
    @php
        $tabs = [
            'agency'    => ['Agency',         true,  null],
            'gst'       => ['GST rates',      false, 'M7'],
            'ai'        => ['AI extraction',  false, 'M11'],
            'reminders' => ['Reminders',      false, 'M13'],
            'branding'  => ['Branding',       false, 'M8'],
            'docs'      => ['Document templates', false, 'M8'],
            'emails'    => ['Email templates',    false, 'M6'],
        ];
    @endphp

    <div class="border-b border-ink-200/70">
        <nav class="-mb-px flex flex-wrap gap-x-6" aria-label="Settings tabs">
            @foreach ($tabs as $key => [$label, $active, $milestone])
                @if ($active)
                    <button
                        type="button"
                        wire:click="$set('activeTab', '{{ $key }}')"
                        @class([
                            'whitespace-nowrap py-3 px-1 border-b-2 text-sm font-medium',
                            'border-brand-700 text-brand-700' => $activeTab === $key,
                            'border-transparent text-ink-500 hover:text-ink-700 hover:border-ink-300' => $activeTab !== $key,
                        ])>
                        {{ $label }}
                    </button>
                @else
                    <span
                        class="whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-sm font-medium text-ink-300 cursor-not-allowed"
                        title="Coming in {{ $milestone }}">
                        {{ $label }}
                    </span>
                @endif
            @endforeach
        </nav>
    </div>

    <div class="mt-6">
        @if ($activeTab === 'agency')
            <form wire:submit.prevent="save" class="space-y-6">

                @if ($saved)
                    <div role="status" class="mt-alert-success">
                        <span>Settings saved. Changes are recorded in the audit log.</span>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="agency_name" class="mt-label">Agency display name <span class="text-red-500">*</span></label>
                        <input id="agency_name" type="text" wire:model="agency_name" class="mt-input" />
                        @error('agency_name') <p class="mt-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="agency_legal_name" class="mt-label">Legal name (as on GST certificate)</label>
                        <input id="agency_legal_name" type="text" wire:model="agency_legal_name" class="mt-input" />
                        @error('agency_legal_name') <p class="mt-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="gstin" class="mt-label">GSTIN</label>
                        <input id="gstin" type="text" wire:model="gstin" maxlength="15" placeholder="27ABCDE1234F1Z5" class="mt-input uppercase" />
                        @error('gstin') <p class="mt-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="pan" class="mt-label">PAN</label>
                        <input id="pan" type="text" wire:model="pan" maxlength="10" placeholder="ABCDE1234F" class="mt-input uppercase" />
                        @error('pan') <p class="mt-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="mt-label">Registered address</label>
                        <textarea id="address" rows="2" wire:model="address" class="mt-textarea"></textarea>
                        @error('address') <p class="mt-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="city" class="mt-label">City</label>
                        <input id="city" type="text" wire:model="city" class="mt-input" />
                        @error('city') <p class="mt-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="state" class="mt-label">Registered state</label>
                        <input id="state" type="text" wire:model="state" placeholder="e.g. Maharashtra" class="mt-input" />
                        <p class="mt-help">Drives CGST+SGST vs IGST split on quotations and invoices.</p>
                        @error('state') <p class="mt-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="pincode" class="mt-label">Pincode</label>
                        <input id="pincode" type="text" wire:model="pincode" maxlength="6" class="mt-input" />
                        @error('pincode') <p class="mt-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone" class="mt-label">Contact phone</label>
                        <input id="phone" type="text" wire:model="phone" class="mt-input" />
                        @error('phone') <p class="mt-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="mt-label">Public email</label>
                        <input id="email" type="email" wire:model="email" class="mt-input" />
                        @error('email') <p class="mt-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="website" class="mt-label">Website</label>
                        <input id="website" type="url" wire:model="website" placeholder="https://marutitravels.example" class="mt-input" />
                        @error('website') <p class="mt-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-ink-200/70">
                    <button type="submit" class="mt-btn-primary">
                        <span wire:loading.remove wire:target="save">Save changes</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
