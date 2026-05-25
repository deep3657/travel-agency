@php
    $u = auth()->user();
    $isAdmin = $u && $u->isAdmin();
    $isAgent = $u && $u->isAgent();

    // [label, route name, svg-path-d, milestone (or null)]
    // Each entry's icon is a single-path Heroicons (outline) `d` attribute.
    // Labels kept short so the nav never wraps at the desktop breakpoint.
    $primaryNav = [
        ['Dashboard',  'dashboard',                            'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', null],
        ['Customers',  'admin.customers.index',                'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-5a4 4 0 11-8 0 4 4 0 018 0zm6 3a3 3 0 11-6 0 3 3 0 016 0z', null],
        ['Vendors',    $isAdmin ? 'admin.vendors.index' : null, 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', $isAdmin ? null : 'admin'],
        ['Packages',   'admin.packages.index',                 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', null],
        ['Enquiries',  'admin.enquiries.index',                'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', null],
        ['Trips',      'admin.trips.index',                    'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7', null],
        ['Bookings',   'admin.bookings.index',                 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', null],
        ['Requests',   'admin.change-requests.index',          'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', null],
        ['Documents',  'admin.supplier-docs.index',            'M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', null],
        ['Reports',    'admin.reports.index',                  'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', null],
    ];
@endphp
<nav x-data="{ open: false }"
     class="bg-white/85 backdrop-blur-md border-b border-ink-200/80 sticky top-0 z-40 supports-[backdrop-filter]:bg-white/70">
    <div class="max-w-[1400px] mx-auto px-3 sm:px-4 lg:px-6">
        <div class="flex items-center justify-between h-16 gap-3">
            {{-- Brand: icon always, wordmark only when there's room (>= 2xl / 1536px) --}}
            <a href="{{ route('dashboard') }}" class="shrink-0 flex items-center gap-2.5">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-brand-600 to-brand-800 text-white shadow-sm shrink-0">
                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 16v-2l-8-5V3.5a1.5 1.5 0 0 0-3 0V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                    </svg>
                </span>
                <span class="hidden 2xl:inline font-display font-bold text-ink-900 tracking-tight text-base whitespace-nowrap">
                    Maruti<span class="text-brand-700"> Travels</span>
                </span>
            </a>

            {{-- Primary nav (desktop) --}}
            <div class="hidden lg:flex items-center gap-px flex-nowrap min-w-0">
                @foreach ($primaryNav as [$label, $routeName, $iconPath, $milestone])
                    @if ($routeName)
                        @php $active = request()->routeIs($routeName); @endphp
                        <a href="{{ route($routeName) }}"
                           class="group relative inline-flex items-center gap-1.5 px-2 py-2 rounded-lg text-[13px] font-medium whitespace-nowrap transition-colors
                                  {{ $active ? 'text-brand-700' : 'text-ink-600 hover:text-ink-900 hover:bg-ink-100/70' }}">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $iconPath }}" />
                            </svg>
                            <span>{{ $label }}</span>
                            @if ($active)
                                <span class="absolute inset-x-2 -bottom-px h-0.5 rounded-full bg-gradient-to-r from-brand-500 to-brand-700"></span>
                            @endif
                        </a>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2 py-2 rounded-lg text-[13px] font-medium text-ink-300 cursor-not-allowed whitespace-nowrap"
                              title="Admin only">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $iconPath }}" />
                            </svg>
                            <span>{{ $label }}</span>
                        </span>
                    @endif
                @endforeach
            </div>

            {{-- Right cluster (desktop) --}}
            <div class="hidden lg:flex items-center gap-1 shrink-0">
                <livewire:admin.reminders-inbox />

                @if ($isAdmin)
                    <a href="{{ route('admin.settings') }}"
                       title="Settings"
                       class="inline-flex items-center justify-center h-9 w-9 rounded-lg transition
                              {{ request()->routeIs('admin.settings') ? 'bg-brand-50 text-brand-700' : 'text-ink-500 hover:text-ink-900 hover:bg-ink-100' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </a>
                @endif

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-1.5 pl-1 pr-1.5 2xl:pr-2.5 py-1 rounded-full text-sm font-medium text-ink-700 hover:bg-ink-100 transition">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-brand-600 to-brand-800 text-white text-xs font-semibold shadow-sm ring-2 ring-white">
                                {{ strtoupper(mb_substr($u?->name ?? '?', 0, 1)) }}
                            </span>
                            <span class="hidden 2xl:flex flex-col items-start text-left leading-tight">
                                <span class="text-[13px] whitespace-nowrap">{{ $u?->name }}</span>
                                <span class="text-[10px] uppercase tracking-wide text-ink-500 font-semibold">
                                    @if ($isAdmin) Admin
                                    @elseif ($isAgent) Agent
                                    @else Staff @endif
                                </span>
                            </span>
                            <svg class="h-4 w-4 text-ink-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-ink-100">
                            <div class="text-sm font-semibold text-ink-800">{{ $u?->name }}</div>
                            <div class="text-xs text-ink-500 truncate">{{ $u?->email }}</div>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')">Profile settings</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Sign out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Mobile hamburger --}}
            <div class="-me-2 flex items-center lg:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-ink-500 hover:text-ink-700 hover:bg-ink-100 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden border-t border-ink-200 bg-white">
        <div class="pt-2 pb-3 space-y-1 px-2">
            @foreach ($primaryNav as [$label, $routeName, $iconPath, $milestone])
                @if ($routeName)
                    @php $active = request()->routeIs($routeName); @endphp
                    <a href="{{ route($routeName) }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
                              {{ $active ? 'bg-brand-50 text-brand-700' : 'text-ink-600 hover:bg-ink-100' }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $iconPath }}" /></svg>
                        {{ $label }}
                    </a>
                @endif
            @endforeach
            @if ($isAdmin)
                <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-ink-600 hover:bg-ink-100">
                    Settings
                </a>
            @endif
        </div>

        <div class="pt-3 pb-2 border-t border-ink-200">
            <div class="px-4">
                <div class="font-medium text-sm text-ink-800">{{ $u?->name }}</div>
                <div class="text-xs text-ink-500">{{ $u?->email }}</div>
            </div>
            <div class="mt-2 space-y-1 px-2">
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-lg text-sm text-ink-700 hover:bg-ink-100">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" class="block px-3 py-2 rounded-lg text-sm text-ink-700 hover:bg-ink-100"
                       onclick="event.preventDefault(); this.closest('form').submit();">
                        Sign out
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>
