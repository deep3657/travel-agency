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

    <div class="border-b border-gray-200">
        <nav class="-mb-px flex flex-wrap gap-x-6" aria-label="Settings tabs">
            @foreach ($tabs as $key => [$label, $active, $milestone])
                @if ($active)
                    <button
                        type="button"
                        wire:click="$set('activeTab', '{{ $key }}')"
                        @class([
                            'whitespace-nowrap py-3 px-1 border-b-2 text-sm font-medium',
                            'border-[var(--mt-accent,#0F4C81)] text-[var(--mt-accent,#0F4C81)]' => $activeTab === $key,
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $activeTab !== $key,
                        ])>
                        {{ $label }}
                    </button>
                @else
                    <span
                        class="whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-sm font-medium text-gray-300 cursor-not-allowed"
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
                    <div role="status" class="rounded-md bg-green-50 border border-green-200 p-3 text-sm text-green-800">
                        Settings saved. Changes are recorded in the audit log.
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="agency_name" class="block text-sm font-medium text-gray-700">Agency display name <span class="text-red-500">*</span></label>
                        <input id="agency_name" type="text" wire:model="agency_name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        @error('agency_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="agency_legal_name" class="block text-sm font-medium text-gray-700">Legal name (as on GST certificate)</label>
                        <input id="agency_legal_name" type="text" wire:model="agency_legal_name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        @error('agency_legal_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="gstin" class="block text-sm font-medium text-gray-700">GSTIN</label>
                        <input id="gstin" type="text" wire:model="gstin" maxlength="15" placeholder="27ABCDE1234F1Z5"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm uppercase" />
                        @error('gstin') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="pan" class="block text-sm font-medium text-gray-700">PAN</label>
                        <input id="pan" type="text" wire:model="pan" maxlength="10" placeholder="ABCDE1234F"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm uppercase" />
                        @error('pan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700">Registered address</label>
                        <textarea id="address" rows="2" wire:model="address"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                        <input id="city" type="text" wire:model="city"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        @error('city') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700">Registered state</label>
                        <input id="state" type="text" wire:model="state" placeholder="e.g. Maharashtra"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        <p class="mt-1 text-xs text-gray-500">Drives CGST+SGST vs IGST split on quotations and invoices.</p>
                        @error('state') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="pincode" class="block text-sm font-medium text-gray-700">Pincode</label>
                        <input id="pincode" type="text" wire:model="pincode" maxlength="6"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        @error('pincode') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Contact phone</label>
                        <input id="phone" type="text" wire:model="phone"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Public email</label>
                        <input id="email" type="email" wire:model="email"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                        <input id="website" type="url" wire:model="website" placeholder="https://marutitravels.example"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        @error('website') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center rounded-md border border-transparent bg-[var(--mt-accent,#0F4C81)] px-4 py-2 text-sm font-medium text-white shadow-sm hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2">
                        <span wire:loading.remove wire:target="save">Save changes</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
