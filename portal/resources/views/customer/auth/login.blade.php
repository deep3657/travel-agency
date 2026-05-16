<x-public-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12">
        <div class="bg-white shadow-sm rounded-lg p-8 w-full max-w-md">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Sign In</h1>
            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 rounded-md p-3 text-sm text-red-700">
                    @foreach($errors->all() as $err)<p>{{ $err }}</p>@endforeach
                </div>
            @endif
            @if(session('status'))
                <div class="mb-4 bg-green-50 border border-green-200 rounded-md p-3 text-sm text-green-700">{{ session('status') }}</div>
            @endif
            <form method="POST" action="{{ route('customer.login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input name="password" type="password" required autocomplete="current-password"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300"> Remember me
                    </label>
                </div>
                <button type="submit" class="w-full py-2 bg-blue-700 text-white font-medium rounded-md hover:bg-blue-800 transition">Sign In</button>
            </form>
            <p class="mt-4 text-center text-sm text-gray-500">New customer? <a href="{{ route('customer.register') }}" class="text-blue-600 hover:underline">Create account</a></p>
        </div>
    </div>
</x-public-layout>
