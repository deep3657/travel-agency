<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Reminder;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class RemindersInbox extends Component
{
    #[Computed]
    public function unreadCount(): int
    {
        $user = auth()->user();
        if ($user === null) {
            return 0;
        }

        return (int) $user->notifications()->whereNull('read_at')->count();
    }

    /**
     * @return Collection<int, Reminder>
     */
    #[Computed]
    public function reminders(): Collection
    {
        return Reminder::with('booking')
            ->whereNull('fired_at')
            ->where('trigger_at', '<=', now()->addDays(7))
            ->orderBy('trigger_at')
            ->limit(10)
            ->get();
    }

    public function markAllRead(): void
    {
        $user = auth()->user();
        if ($user !== null) {
            $user->unreadNotifications()->update(['read_at' => now()]);
        }
        $this->dispatch('notifications-read');
    }

    public function render(): View
    {
        return view('livewire.admin.reminders-inbox');
    }
}
