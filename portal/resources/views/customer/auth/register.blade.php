<x-public-layout title="Create account">
    <div class="min-h-[80vh] flex items-center justify-center bg-ink-50 py-12 px-4">
        <div class="w-full max-w-lg">
            <div class="text-center mb-6">
                <a href="{{ route('home') }}" class="inline-flex">
                    <x-brand-mark size="lg" />
                </a>
            </div>

            <div class="mt-card p-8">
                <h1 class="font-display text-2xl font-bold text-ink-900 mb-1">Create your account</h1>
                <p class="text-sm text-ink-500 mb-6">Get personalised quotations, track trips and manage bookings.</p>

                @if($errors->any())
                    <div class="mt-alert-error mb-4">
                        <svg class="h-5 w-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/></svg>
                        <div>@foreach($errors->all() as $err)<p>{{ $err }}</p>@endforeach</div>
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.register') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mt-label">Full name</label>
                        <input name="name" type="text" value="{{ old('name') }}" required autocomplete="name" class="mt-input" placeholder="e.g. Rohan Sharma">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="mt-label">Email address</label>
                            <input name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="mt-input" placeholder="you@example.com">
                        </div>
                        <div>
                            <label class="mt-label">Phone (optional)</label>
                            <input name="phone" type="tel" value="{{ old('phone') }}" class="mt-input" placeholder="+91 98765 43210">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="mt-label">Password</label>
                            <input name="password" type="password" required autocomplete="new-password" class="mt-input" placeholder="At least 8 characters">
                        </div>
                        <div>
                            <label class="mt-label">Confirm password</label>
                            <input name="password_confirmation" type="password" required autocomplete="new-password" class="mt-input" placeholder="Repeat password">
                        </div>
                    </div>
                    <button type="submit" class="mt-btn-primary w-full">Create account</button>
                </form>

                <p class="mt-6 text-center text-sm text-ink-500">
                    Already have an account? <a href="{{ route('customer.login') }}" class="text-brand-700 font-medium hover:text-brand-800">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</x-public-layout>
