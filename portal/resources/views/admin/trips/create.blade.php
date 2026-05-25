<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="New trip"
            subtitle="Plan a new trip directly, or convert from an existing enquiry."
            :breadcrumbs="[
                ['label' => 'Trips', 'href' => route('admin.trips.index')],
                ['label' => 'New trip'],
            ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />

            <livewire:admin.trips.trip-form />

            {{-- Alternate path: from enquiry --}}
            <div class="mt-card">
                <div class="mt-card-body flex items-start gap-4">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-700 shrink-0">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h8m-8 4h8m-8 4h5m-9 5h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </span>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold text-ink-900">Already have an enquiry?</h3>
                        <p class="text-sm text-ink-600 mt-0.5">
                            Convert directly from the enquiry — we'll carry the customer, destination, dates and notes over for you.
                        </p>
                    </div>
                    <a href="{{ route('admin.enquiries.index') }}" class="mt-btn-secondary mt-btn-sm shrink-0">
                        Browse enquiries
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
