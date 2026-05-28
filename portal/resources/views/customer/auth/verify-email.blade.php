<x-public-layout title="Verify your email">
    <div class="min-h-[80vh] flex items-center justify-center bg-ink-50 py-12 px-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-6">
                <a href="{{ route('home') }}" class="inline-flex">
                    <x-brand-mark size="lg" />
                </a>
            </div>

            <div class="mt-card p-8 text-center">
                <div class="mx-auto h-12 w-12 inline-flex items-center justify-center rounded-full bg-brand-50 text-brand-700 mb-4">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 class="font-display text-2xl font-bold text-ink-900 mb-2">Verify your email</h1>
                <p class="text-sm text-ink-600 mb-6">
                    We sent a verification link to
                    <strong class="text-ink-900">{{ auth('customer')->user()?->email }}</strong>.
                    Click the link inside that email to activate your account.
                </p>

                @if (session('status') === 'verification-link-sent')
                    <div class="mt-alert-success mb-4 text-left">
                        <svg class="h-5 w-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>A fresh verification link has been sent to your email address.</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.verification.send') }}" class="space-y-3">
                    @csrf
                    <button type="submit" class="mt-btn-primary w-full">Resend verification email</button>
                </form>

                <p class="text-xs text-ink-500 mt-5">
                    Wrong account?
                    <a href="#"
                       onclick="event.preventDefault(); document.getElementById('customer-logout-form').submit();"
                       class="text-brand-700 hover:underline">Log out</a>
                </p>
                <form id="customer-logout-form" method="POST" action="{{ route('customer.logout') }}" class="hidden">
                    @csrf
                </form>
            </div>

            <p class="text-center text-xs text-ink-500 mt-6">
                Didn't get the email? Check your spam folder, or
                <a href="{{ route('contact') }}" class="text-brand-700 hover:underline">contact us</a>.
            </p>
        </div>
    </div>
</x-public-layout>
