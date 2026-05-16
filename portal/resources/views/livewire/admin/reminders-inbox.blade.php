<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="relative p-2 rounded-full hover:bg-gray-100 text-gray-500 hover:text-gray-700 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if($this->unreadCount > 0)
            <span class="absolute top-0.5 right-0.5 w-4 h-4 bg-red-500 text-white rounded-full text-xs flex items-center justify-center font-bold">{{ $this->unreadCount > 9 ? '9+' : $this->unreadCount }}</span>
        @endif
    </button>

    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white shadow-lg rounded-lg border z-50">
        <div class="flex justify-between items-center px-4 py-2 border-b">
            <span class="font-semibold text-sm">Reminders</span>
            @if($this->unreadCount > 0)
                <button wire:click="markAllRead" class="text-xs text-blue-600 hover:underline">Mark all read</button>
            @endif
        </div>
        <div class="max-h-80 overflow-y-auto">
            @forelse($this->reminders as $r)
                <div class="px-4 py-3 border-b last:border-0 hover:bg-gray-50">
                    <div class="text-sm font-medium text-gray-800">{{ ucfirst(str_replace('_', ' ', $r->reminder_type)) }}</div>
                    <div class="text-xs text-gray-500">Booking: {{ $r->booking?->booking_ref ?? '—' }}</div>
                    <div class="text-xs text-gray-400">{{ $r->trigger_at->format('d M Y, H:i') }}</div>
                </div>
            @empty
                <div class="px-4 py-6 text-center text-sm text-gray-400">No reminders.</div>
            @endforelse
        </div>
    </div>
</div>
