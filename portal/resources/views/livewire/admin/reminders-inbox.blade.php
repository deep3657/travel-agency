<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="relative hover:bg-ink-100 rounded-lg p-2 text-ink-600 hover:text-ink-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if($this->unreadCount > 0)
            <span class="mt-pill-amber absolute -top-1 -right-1 min-w-[1.25rem] h-5 px-1 justify-center text-[10px] font-bold">{{ $this->unreadCount > 9 ? '9+' : $this->unreadCount }}</span>
        @endif
    </button>

    <div x-show="open" x-cloak @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white border border-ink-200 rounded-xl shadow-card z-50">
        <div class="flex justify-between items-center px-4 py-3 border-b border-ink-200/70">
            <span class="font-semibold text-sm text-ink-900">Reminders</span>
            @if($this->unreadCount > 0)
                <button wire:click="markAllRead" class="text-xs text-brand-700 hover:text-brand-800 hover:underline">Mark all read</button>
            @endif
        </div>
        <div class="max-h-80 overflow-y-auto">
            @forelse($this->reminders as $r)
                <div class="px-4 py-3 border-b border-ink-100 last:border-0 hover:bg-ink-50">
                    <div class="flex items-start gap-2">
                        <span class="inline-block h-2 w-2 rounded-full bg-brand-500 mt-1.5 shrink-0"></span>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-ink-800">{{ ucfirst(str_replace('_', ' ', $r->reminder_type)) }}</div>
                            <div class="text-xs text-ink-500">Booking: {{ $r->booking?->booking_ref ?? '—' }}</div>
                            <div class="text-xs text-ink-400">{{ $r->trigger_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 py-6 text-center text-sm text-ink-400">No reminders.</div>
            @endforelse
        </div>
    </div>
</div>
