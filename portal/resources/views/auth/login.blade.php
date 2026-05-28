<x-guest-layout>
    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 mb-3 rounded-full bg-brand-50 text-brand-700 text-[11px] font-semibold uppercase tracking-wide">
        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.657 1.343-3 3-3s3 1.343 3 3-1.343 3-3 3-3-1.343-3-3zM2 12s3-7 10-7 10 7 10 7-3 7-10 7S2 12 2 12z"/></svg>
        Staff portal
    </div>
    <h1 class="font-display text-2xl font-bold text-ink-900 mb-1">Staff login</h1>
    <p class="text-sm text-ink-500 mb-6">Sign in with your Maruti Travels staff account (admin or agent).</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@marutitravels.in" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-ink-600">
                <input id="remember_me" type="checkbox" class="rounded border-ink-300 text-brand-700 focus:ring-brand-500" name="remember">
                {{ __('Remember me') }}
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-brand-700 hover:text-brand-800" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <button type="submit" class="mt-btn-primary w-full">
            {{ __('Sign in') }}
        </button>
    </form>
</x-guest-layout>
