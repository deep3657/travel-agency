import './bootstrap';

// Note: Alpine is bundled with Livewire 3 (@livewireScripts) and exposed on
// window.Alpine automatically. Importing alpinejs separately here caused a
// "Detected multiple instances of Alpine running" error that silently broke
// wire:click handlers. Existing x-data / x-show / x-on directives in Blade
// templates continue to work via Livewire's bundled Alpine.
