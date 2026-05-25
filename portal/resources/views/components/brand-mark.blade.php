@props(['size' => 'md', 'showText' => true])
@php
    $sizes = [
        'sm' => 'h-7 w-7 text-sm',
        'md' => 'h-9 w-9 text-base',
        'lg' => 'h-12 w-12 text-lg',
    ];
    $box = $sizes[$size] ?? $sizes['md'];
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5']) }}>
    <span class="inline-flex {{ $box }} items-center justify-center rounded-lg bg-gradient-to-br from-brand-600 to-brand-800 text-white font-bold shadow-sm">
        <svg class="h-1/2 w-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M21 16v-2l-8-5V3.5a1.5 1.5 0 0 0-3 0V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
        </svg>
    </span>
    @if ($showText)
        <span class="font-display font-bold text-ink-900 tracking-tight {{ $size === 'lg' ? 'text-xl' : 'text-base' }}">
            Maruti<span class="text-brand-700"> Travels</span>
        </span>
    @endif
</span>
