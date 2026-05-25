<x-app-layout>
    <x-slot name="header">
        <x-page-header
            :title="$package->title"
            :breadcrumbs="[
                ['label' => 'Packages', 'href' => route('admin.packages.index')],
                ['label' => $package->title],
            ]">
            @can('update', $package)
                <a href="{{ route('admin.packages.edit', $package->ulid) }}" class="mt-btn-secondary mt-btn-sm">
                    Edit
                </a>
            @endcan
        </x-page-header>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />

            <div class="mt-card mt-card-body">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-ink-500">Status</dt>
                        <dd class="mt-1">
                            <x-status-pill :status="$package->status" />
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-ink-500">Price from</dt>
                        <dd class="text-ink-900 font-semibold mt-0.5">₹{{ number_format($package->price_from_inr->toRupees()) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-ink-500">Duration</dt>
                        <dd class="text-ink-800 mt-0.5">{{ $package->duration_days }}D / {{ $package->duration_nights }}N</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-ink-500">Destinations</dt>
                        <dd class="text-ink-800 mt-0.5">{{ $package->destinations }}</dd>
                    </div>
                </dl>
                @if($package->short_description)
                    <div class="mt-5 pt-5 border-t border-ink-200/70">
                        <p class="text-sm text-ink-700">{{ $package->short_description }}</p>
                    </div>
                @endif
            </div>

            @can('update', $package)
                <div class="mt-card mt-card-body">
                    @if($package->status === 'active')
                        <form method="POST" action="{{ route('admin.packages.show', $package->ulid) }}" class="inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="action" value="unpublish">
                            <button class="mt-btn-secondary mt-btn-sm">Unpublish</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.packages.show', $package->ulid) }}" class="inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="action" value="publish">
                            <button class="mt-btn-primary mt-btn-sm">Publish</button>
                        </form>
                    @endif
                </div>
            @endcan

            <div class="mt-card mt-card-body">
                <h3 class="font-semibold text-ink-900 mb-4">Itinerary days ({{ $package->itineraryDays->count() }})</h3>
                @forelse($package->itineraryDays as $day)
                    <div class="border-l-2 border-brand-200 pl-4 mb-3">
                        <div class="font-medium text-ink-900">Day {{ $day->day_number }}: {{ $day->title }}</div>
                        @if($day->description)<p class="text-sm text-ink-500 mt-0.5">{{ $day->description }}</p>@endif
                    </div>
                @empty
                    <p class="text-sm text-ink-400">No itinerary days added.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
