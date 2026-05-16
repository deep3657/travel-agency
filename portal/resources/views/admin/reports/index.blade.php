<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Reports</h2></x-slot>
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="bg-white shadow-sm rounded-lg p-5">
                <h3 class="font-semibold mb-2">Bookings Register</h3>
                <p class="text-sm text-gray-500 mb-3">Full list of all bookings with customer and vendor details.</p>
                <a href="{{ route('admin.reports.export.bookings') }}" class="inline-flex items-center px-4 py-2 bg-green-700 text-white text-sm rounded-md hover:bg-green-800">Download Excel</a>
            </div>
            @if($isAdmin)
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <h3 class="font-semibold mb-2">Sales & Profit</h3>
                    <p class="text-sm text-gray-500 mb-3">Revenue, costs, and margin analysis. Admin only.</p>
                    <a href="{{ route('admin.reports.export.salesProfit') }}" class="inline-flex items-center px-4 py-2 bg-purple-700 text-white text-sm rounded-md hover:bg-purple-800">Download Excel</a>
                </div>
            @endif
            <div class="bg-white shadow-sm rounded-lg p-5">
                <h3 class="font-semibold mb-2">Enquiry Conversion</h3>
                <p class="text-sm text-gray-500 mb-3">Enquiry to trip conversion rates.</p>
                <span class="text-sm text-gray-400">View below ↓</span>
            </div>
        </div>

        {{-- Bookings table preview --}}
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-5 py-4 border-b">
                <h3 class="font-semibold text-gray-700">Bookings Register Preview</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs text-gray-500">Ref</th>
                            <th class="px-4 py-2 text-left text-xs text-gray-500">Customer</th>
                            <th class="px-4 py-2 text-left text-xs text-gray-500">Type</th>
                            <th class="px-4 py-2 text-left text-xs text-gray-500">Vendor</th>
                            <th class="px-4 py-2 text-left text-xs text-gray-500">Sale</th>
                            <th class="px-4 py-2 text-left text-xs text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($bookings as $b)
                            <tr>
                                <td class="px-4 py-2 font-mono text-sm">{{ $b->booking_ref }}</td>
                                <td class="px-4 py-2 text-sm">{{ $b->customer->name }}</td>
                                <td class="px-4 py-2 text-sm">{{ ucfirst($b->booking_type) }}</td>
                                <td class="px-4 py-2 text-sm">{{ $b->vendor?->name ?? '—' }}</td>
                                <td class="px-4 py-2 text-sm">₹{{ number_format($b->sale_amount?->toRupees() ?? 0) }}</td>
                                <td class="px-4 py-2 text-sm">{{ $b->status }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-4 text-center text-sm text-gray-400">No bookings.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($isAdmin)
            {{-- AI Extraction --}}
            <div class="bg-white shadow-sm rounded-lg">
                <div class="flex justify-between items-center px-5 py-4 border-b">
                    <h3 class="font-semibold text-gray-700">AI Extraction Jobs</h3>
                    <a href="{{ route('admin.reports.ai-extraction') }}" class="text-sm text-blue-600 hover:underline">View all</a>
                </div>
                <p class="px-5 py-3 text-sm text-gray-500">Monitor AI document extraction usage and costs.</p>
            </div>
        @endif
    </div>
</x-app-layout>
