<div class="space-y-6">
    <div class="bg-blue-700 text-white rounded-lg p-6">
        <h2 class="text-2xl font-bold">Welcome back, {{ $customerName }}!</h2>
        <p class="text-blue-100 mt-1">Here's an overview of your travel with us.</p>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <a href="{{ route('customer.enquiries') }}" class="bg-white rounded-lg shadow-sm p-5 hover:shadow-md transition">
            <div class="text-3xl font-bold text-blue-700">{{ $enquiryCount }}</div>
            <div class="text-sm text-gray-500 mt-1">My Enquiries</div>
        </a>
        <a href="{{ route('customer.trips') }}" class="bg-white rounded-lg shadow-sm p-5 hover:shadow-md transition">
            <div class="text-3xl font-bold text-green-700">{{ $tripCount }}</div>
            <div class="text-sm text-gray-500 mt-1">My Trips</div>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-5">
        <h3 class="font-semibold mb-3">Quick Actions</h3>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('packages.index') }}" class="px-4 py-2 bg-blue-50 text-blue-700 rounded-md text-sm hover:bg-blue-100">Browse Packages</a>
            <a href="{{ route('customer.profile') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200">Edit Profile</a>
        </div>
    </div>
</div>
