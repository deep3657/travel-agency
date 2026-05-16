@php
    $u = auth()->user();
    $isAdmin = $u && $u->isAdmin();
    $isAgent = $u && $u->isAgent();

    // Each entry: [label, route name OR null if not yet wired, milestone where it lights up]
    $primaryNav = [
        ['Dashboard',       'dashboard',                        null],
        ['Customers',       'admin.customers.index',            null],
        ['Vendors',         $isAdmin ? 'admin.vendors.index' : null, $isAdmin ? null : 'admin'],
        ['Packages',        'admin.packages.index',             null],
        ['Enquiries',       'admin.enquiries.index',            null],
        ['Trips',           'admin.trips.index',                null],
        ['Bookings',        'admin.bookings.index',             null],
        ['Change Requests', 'admin.change-requests.index',      null],
        ['Reports',         'admin.reports.index',              null],
    ];
@endphp
<nav x-data="{ open: false }" class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-md bg-[var(--mt-accent,#0F4C81)] text-white font-semibold">M</span>
                        <span class="hidden sm:inline-block text-lg font-semibold text-gray-800">Maruti Travels</span>
                    </a>
                </div>

                <div class="hidden lg:flex lg:items-center lg:ms-10 lg:space-x-2">
                    @foreach ($primaryNav as [$label, $routeName, $milestone])
                        @if ($routeName)
                            <x-nav-link :href="route($routeName)" :active="request()->routeIs($routeName)">
                                {{ $label }}
                            </x-nav-link>
                        @else
                            <span
                                class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-300 cursor-not-allowed"
                                title="Coming in {{ $milestone }}">
                                {{ $label }}
                            </span>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="hidden lg:flex lg:items-center lg:ms-6 lg:space-x-4">
                <livewire:admin.reminders-inbox />

                @if ($isAdmin)
                    <x-nav-link :href="route('admin.settings')" :active="request()->routeIs('admin.settings')">
                        Settings
                    </x-nav-link>
                @endif

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-600 bg-white hover:text-gray-800 focus:outline-none transition">
                            <div class="flex flex-col items-start text-left">
                                <span>{{ $u?->name }}</span>
                                <span class="text-xs text-gray-400">
                                    @if ($isAdmin) Admin
                                    @elseif ($isAgent) Agent
                                    @else Staff @endif
                                </span>
                            </div>
                            <svg class="fill-current h-4 w-4 ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @foreach ($primaryNav as [$label, $routeName, $milestone])
                @if ($routeName)
                    <x-responsive-nav-link :href="route($routeName)" :active="request()->routeIs($routeName)">
                        {{ $label }}
                    </x-responsive-nav-link>
                @else
                    <span class="block ps-3 pe-4 py-2 text-base font-medium text-gray-300 cursor-not-allowed">
                        {{ $label }}
                        <span class="text-xs">(coming in {{ $milestone }})</span>
                    </span>
                @endif
            @endforeach
            @if ($isAdmin)
                <x-responsive-nav-link :href="route('admin.settings')" :active="request()->routeIs('admin.settings')">
                    Settings
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ $u?->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ $u?->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">Profile</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
