<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">{{ $package->title }}</h2>
            <div class="flex gap-2">
                @can('update', $package)
                    <a href="{{ route('admin.packages.edit', $package->ulid) }}" class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200">Edit</a>
                @endcan
            </div>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-3 text-sm text-green-800">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium {{ $package->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($package->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Price From</dt>
                        <dd class="font-semibold">₹{{ number_format($package->price_from_inr->toRupees()) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Duration</dt>
                        <dd>{{ $package->duration_days }}D / {{ $package->duration_nights }}N</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Destinations</dt>
                        <dd>{{ $package->destinations }}</dd>
                    </div>
                </div>
                @if($package->short_description)
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-gray-600">{{ $package->short_description }}</p>
                    </div>
                @endif
            </div>

            {{-- Publish / Unpublish --}}
            @can('update', $package)
                <div class="bg-white shadow-sm rounded-lg p-4 mb-6">
                    @if($package->status === 'active')
                        <form method="POST" action="{{ route('admin.packages.show', $package->ulid) }}" class="inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="action" value="unpublish">
                            <button class="px-4 py-2 bg-yellow-100 text-yellow-800 text-sm rounded-md hover:bg-yellow-200">Unpublish</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.packages.show', $package->ulid) }}" class="inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="action" value="publish">
                            <button class="px-4 py-2 bg-green-100 text-green-800 text-sm rounded-md hover:bg-green-200">Publish</button>
                        </form>
                    @endif
                </div>
            @endcan

            {{-- Itinerary Days --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Itinerary Days ({{ $package->itineraryDays->count() }})</h3>
                @forelse($package->itineraryDays as $day)
                    <div class="border-l-2 border-blue-200 pl-4 mb-3">
                        <div class="font-medium">Day {{ $day->day_number }}: {{ $day->title }}</div>
                        @if($day->description)<p class="text-sm text-gray-500">{{ $day->description }}</p>@endif
                    </div>
                @empty
                    <p class="text-sm text-gray-400">No itinerary days added.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
