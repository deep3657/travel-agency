<x-public-layout title="Sign in">
    <div class="min-h-[80vh] flex items-center justify-center bg-ink-50 py-12 px-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-6">
                <a href="{{ route('home') }}" class="inline-flex">
                    <x-brand-mark size="lg" />
                </a>
            </div>

            <div class="mt-card p-8">
                <h1 class="font-display text-2xl font-bold text-ink-900 mb-1">Welcome back</h1>
                <p class="text-sm text-ink-500 mb-6">Sign in to access your trips, enquiries and bookings.</p>

                @if($errors->any())
                    <div class="mt-alert-error mb-4">
                        <svg class="h-5 w-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/></svg>
                        <div>@foreach($errors->all() as $err)<p>{{ $err }}</p>@endforeach</div>
                    </div>
                @endif
                @if(session('status'))
                    <div class="mt-alert-success mb-4">
                        <svg class="h-5 w-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.login') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mt-label">Email address</label>
                        <input name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                            class="mt-input" placeholder="you@example.com">
                    </div>
                    <div>
                        <label class="mt-label">Password</label>
                        <input name="password" type="password" required autocomplete="current-password"
                            class="mt-input" placeholder="••••••••">
                    </div>
                    <label class="flex items-center gap-2 text-sm text-ink-600">
                        <input type="checkbox" name="remember" class="rounded border-ink-300 text-brand-700 focus:ring-brand-500"> Remember me
                    </label>
                    <button type="submit" class="mt-btn-primary w-full">Sign in</button>
                </form>

                <p class="mt-6 text-center text-sm text-ink-500">
                    New here? <a href="{{ route('customer.register') }}" class="text-brand-700 font-medium hover:text-brand-800">Create an account</a>
                </p>
            </div>

            <p class="text-center text-xs text-ink-500 mt-6">
                Maruti Travels staff? <a href="{{ route('login') }}" class="text-brand-700 hover:underline">Sign in to staff portal</a>
            </p>
        </div>
    </div>
</x-public-layout>
