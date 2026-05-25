@props([
    'label' => '',
    'value' => '',
    'icon' => null,
    'iconHtml' => null,
    'tone' => 'brand',
    'trend' => null,
    'trendUp' => true,
    'href' => null,
])
@php
    // Tone presets:
    //   - badge  : gradient pill behind the icon
    //   - bar    : thin gradient stripe at the top of the card (premium accent)
    $tones = [
        'brand'   => ['badge' => 'bg-gradient-to-br from-brand-500 to-brand-700 text-white',     'bar' => 'from-brand-400 via-brand-600 to-brand-800'],
        'emerald' => ['badge' => 'bg-gradient-to-br from-emerald-400 to-emerald-600 text-white', 'bar' => 'from-emerald-300 via-emerald-500 to-emerald-700'],
        'amber'   => ['badge' => 'bg-gradient-to-br from-amber-400 to-amber-600 text-white',     'bar' => 'from-amber-300 via-amber-500 to-amber-600'],
        'violet'  => ['badge' => 'bg-gradient-to-br from-violet-400 to-violet-600 text-white',   'bar' => 'from-violet-300 via-violet-500 to-violet-700'],
        'rose'    => ['badge' => 'bg-gradient-to-br from-rose-400 to-rose-600 text-white',       'bar' => 'from-rose-300 via-rose-500 to-rose-700'],
        'sky'     => ['badge' => 'bg-gradient-to-br from-sky-400 to-sky-600 text-white',         'bar' => 'from-sky-300 via-sky-500 to-sky-700'],
        'gray'    => ['badge' => 'bg-gradient-to-br from-ink-400 to-ink-600 text-white',         'bar' => 'from-ink-300 via-ink-400 to-ink-500'],
    ];
    $toneSet = $tones[$tone] ?? $tones['brand'];
    $tag = $href ? 'a' : 'div';
@endphp
<{{ $tag }} @if($href) href="{{ $href }}" @endif
    class="group relative overflow-hidden bg-white rounded-xl border border-ink-200/70 shadow-card p-5 flex flex-col gap-3
           transition-all duration-200 {{ $href ? 'hover:-translate-y-0.5 hover:shadow-card-hover hover:border-ink-300/80' : '' }}">
    {{-- Top accent stripe --}}
    <span aria-hidden="true" class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r {{ $toneSet['bar'] }}"></span>

    <div class="flex items-start justify-between">
        <span class="text-[11px] font-semibold uppercase tracking-wider text-ink-500">{{ $label }}</span>
        @if($iconHtml)
            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl shadow-sm {{ $toneSet['badge'] }}">
                {!! $iconHtml !!}
            </span>
        @elseif($icon)
            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl shadow-sm {{ $toneSet['badge'] }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                </svg>
            </span>
        @endif
    </div>
    <div class="flex items-end justify-between gap-2">
        <span class="font-display text-3xl sm:text-[2rem] font-bold text-ink-900 leading-none tracking-tight">{{ $value }}</span>
        @if(! is_null($trend))
            <span class="inline-flex items-center gap-1 text-xs font-semibold {{ $trendUp ? 'text-emerald-600' : 'text-rose-600' }}">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if($trendUp)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    @endif
                </svg>
                {{ $trend }}
            </span>
        @endif
    </div>
    @if($href)
        <span class="absolute bottom-3 right-4 text-ink-300 group-hover:text-brand-600 transition opacity-0 group-hover:opacity-100 text-xs font-semibold">
            View →
        </span>
    @endif
</{{ $tag }}>
