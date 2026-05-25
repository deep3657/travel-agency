@php
    $customer = auth('customer')->user()?->customer;
@endphp
<div class="space-y-8">
    <x-page-header title="My Enquiries" subtitle="Travel ideas you've shared with us — and our progress on each.">
        <a href="{{ route('packages.index') }}" class="mt-btn-secondary mt-btn-sm">Browse packages</a>
    </x-page-header>

    {{-- New enquiry form --}}
    <div class="mt-card">
        <div class="mt-card-header">
            <div>
                <h3 class="font-semibold text-ink-900">Start a new enquiry</h3>
                <p class="text-xs text-ink-500 mt-0.5">
                    Sending as <span class="font-medium text-ink-700">{{ $customer?->name }}</span>
                    @if($customer?->phone)
                        · {{ $customer->phone }}
                    @endif
                </p>
            </div>
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"/></svg>
            </span>
        </div>

        <form method="POST" action="{{ route('customer.enquiries.store') }}" class="mt-card-body space-y-5">
            @csrf

            @if ($errors->any())
                <div class="mt-alert-error" role="alert">
                    <svg class="h-5 w-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/></svg>
                    <div>
                        <p class="font-medium">Please correct the following:</p>
                        <ul class="mt-1 list-disc list-inside text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="enquiry_type" class="mt-label">What are you planning?</label>
                    <select id="enquiry_type" name="enquiry_type" class="mt-select" required>
                        <option value="package" {{ old('enquiry_type', 'package') === 'package' ? 'selected' : '' }}>Holiday package</option>
                        <option value="flight" {{ old('enquiry_type') === 'flight' ? 'selected' : '' }}>Flight only</option>
                        <option value="hotel" {{ old('enquiry_type') === 'hotel' ? 'selected' : '' }}>Hotel only</option>
                        <option value="mixed" {{ old('enquiry_type') === 'mixed' ? 'selected' : '' }}>Flight + hotel / custom</option>
                    </select>
                </div>
                <div>
                    <label for="destination" class="mt-label">Destination</label>
                    <input id="destination" name="destination" type="text" required maxlength="120"
                           value="{{ old('destination') }}" placeholder="e.g. Bali, Goa, Kashmir"
                           class="mt-input">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="travel_from" class="mt-label">Travel from <span class="text-ink-400 font-normal">(optional)</span></label>
                    <input id="travel_from" name="travel_from" type="date" value="{{ old('travel_from') }}" min="{{ now()->toDateString() }}" class="mt-input">
                </div>
                <div>
                    <label for="travel_to" class="mt-label">Travel to <span class="text-ink-400 font-normal">(optional)</span></label>
                    <input id="travel_to" name="travel_to" type="date" value="{{ old('travel_to') }}" min="{{ now()->toDateString() }}" class="mt-input">
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <label for="pax_adult" class="mt-label">Adults</label>
                    <input id="pax_adult" name="pax_adult" type="number" min="1" max="20" required
                           value="{{ old('pax_adult', 2) }}" class="mt-input">
                </div>
                <div>
                    <label for="pax_child" class="mt-label">Children</label>
                    <input id="pax_child" name="pax_child" type="number" min="0" max="20"
                           value="{{ old('pax_child', 0) }}" class="mt-input">
                </div>
                <div>
                    <label for="budget_min" class="mt-label">Budget min (₹) <span class="text-ink-400 font-normal">opt.</span></label>
                    <input id="budget_min" name="budget_min" type="number" min="0" step="1000"
                           value="{{ old('budget_min') }}" placeholder="50000" class="mt-input">
                </div>
                <div>
                    <label for="budget_max" class="mt-label">Budget max (₹) <span class="text-ink-400 font-normal">opt.</span></label>
                    <input id="budget_max" name="budget_max" type="number" min="0" step="1000"
                           value="{{ old('budget_max') }}" placeholder="100000" class="mt-input">
                </div>
            </div>

            <div>
                <label for="special_requirements" class="mt-label">Anything special we should know?</label>
                <textarea id="special_requirements" name="special_requirements" rows="3" maxlength="5000"
                          placeholder="Dietary preferences, accessibility needs, preferred airlines, special occasions…"
                          class="mt-textarea">{{ old('special_requirements') }}</textarea>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-2 border-t border-ink-100">
                <p class="text-xs text-ink-500">We typically respond within one business day.</p>
                <button type="submit" class="mt-btn-primary">
                    Send enquiry
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>
    </div>

    {{-- Existing enquiries list --}}
    <div>
        <h3 class="font-semibold text-ink-900 mb-3">Your enquiries</h3>
        @if($this->enquiries->isEmpty())
            <div class="mt-card">
                <x-empty-state
                    title="No enquiries yet"
                    description="Use the form above to tell us where you'd like to go. Every enquiry gets a personalised quotation." />
            </div>
        @else
            <div class="mt-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="mt-table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Destination</th>
                                <th>Travel from</th>
                                <th>Status</th>
                                <th class="text-right">Submitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($this->enquiries as $e)
                                <tr>
                                    <td class="capitalize">{{ str_replace('_', ' ', $e->enquiry_type) }}</td>
                                    <td>{{ $e->destination ?? '—' }}</td>
                                    <td>{{ $e->travel_from?->format('d M Y') ?? '—' }}</td>
                                    <td><x-status-pill :status="$e->status" /></td>
                                    <td class="text-right text-xs text-ink-500">{{ $e->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 border-t border-ink-200/70">{{ $this->enquiries->links() }}</div>
            </div>
        @endif
    </div>
</div>
