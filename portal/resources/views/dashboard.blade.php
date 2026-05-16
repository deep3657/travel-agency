<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Dashboard</h2></x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stat cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <div class="text-3xl font-bold text-blue-700">{{ $totalCustomers }}</div>
                    <div class="text-sm text-gray-500 mt-1">Total Customers</div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <div class="text-3xl font-bold text-green-700">{{ $totalBookings }}</div>
                    <div class="text-sm text-gray-500 mt-1">Total Bookings</div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <div class="text-3xl font-bold text-yellow-600">{{ $pendingEnquiries }}</div>
                    <div class="text-sm text-gray-500 mt-1">Pending Enquiries</div>
                </div>
                @if($isAdmin)
                    <div class="bg-white shadow-sm rounded-lg p-5">
                        <div class="text-3xl font-bold text-purple-700">₹{{ number_format($monthlyRevenue, 0) }}</div>
                        <div class="text-sm text-gray-500 mt-1">Monthly Revenue</div>
                    </div>
                @else
                    <div class="bg-white shadow-sm rounded-lg p-5">
                        <div class="text-3xl font-bold text-gray-700">{{ $totalEnquiries }}</div>
                        <div class="text-sm text-gray-500 mt-1">My Enquiries</div>
                    </div>
                @endif
            </div>

            {{-- Recent Enquiries --}}
            <div class="bg-white shadow-sm rounded-lg">
                <div class="flex justify-between items-center px-5 py-4 border-b">
                    <h3 class="font-semibold text-gray-700">Recent Enquiries</h3>
                    <a href="{{ route('admin.enquiries.index') }}" class="text-sm text-blue-600 hover:underline">View all</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs text-gray-500">Customer</th>
                                <th class="px-4 py-2 text-left text-xs text-gray-500">Type</th>
                                <th class="px-4 py-2 text-left text-xs text-gray-500">Destination</th>
                                <th class="px-4 py-2 text-left text-xs text-gray-500">Status</th>
                                <th class="px-4 py-2 text-left text-xs text-gray-500">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recentEnquiries as $e)
                                <tr>
                                    <td class="px-4 py-2 text-sm">{{ $e->customer->name }}</td>
                                    <td class="px-4 py-2 text-sm">{{ ucfirst($e->enquiry_type) }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $e->destination ?? '—' }}</td>
                                    <td class="px-4 py-2"><span class="px-2 py-0.5 rounded text-xs bg-blue-50 text-blue-700">{{ $e->status }}</span></td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $e->created_at->format('d M') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-4 py-4 text-center text-sm text-gray-400">No enquiries yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Upcoming Travel --}}
            @if($upcomingTrips->isNotEmpty())
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-5 py-4 border-b">
                        <h3 class="font-semibold text-gray-700">Upcoming Travel (7 days)</h3>
                    </div>
                    <div class="divide-y">
                        @foreach($upcomingTrips as $trip)
                            <div class="px-5 py-3 flex justify-between items-center">
                                <div>
                                    <span class="font-medium text-sm">{{ $trip->name }}</span>
                                    <span class="ml-2 text-sm text-gray-500">{{ $trip->customer->name }}</span>
                                </div>
                                <span class="text-sm text-gray-500">{{ $trip->travel_start->format('d M Y') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
