<x-public-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12">
        <div class="bg-white shadow-sm rounded-lg p-8 w-full max-w-md">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Create Account</h1>
            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 rounded-md p-3 text-sm text-red-700">
                    @foreach($errors->all() as $err)<p>{{ $err }}</p>@endforeach
                </div>
            @endif
            <form method="POST" action="{{ route('customer.register') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input name="name" type="text" value="{{ old('name') }}" required autocomplete="name"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input name="phone" type="tel" value="{{ old('phone') }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input name="password" type="password" required autocomplete="new-password"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input name="password_confirmation" type="password" required autocomplete="new-password"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="w-full py-2 bg-blue-700 text-white font-medium rounded-md hover:bg-blue-800 transition">Create Account</button>
            </form>
            <p class="mt-4 text-center text-sm text-gray-500">Already have an account? <a href="{{ route('customer.login') }}" class="text-blue-600 hover:underline">Sign in</a></p>
        </div>
    </div>
</x-public-layout>
