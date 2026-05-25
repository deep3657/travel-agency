@props([
    'title' => 'Nothing here yet',
    'description' => null,
    'icon' => null,
])
<div class="mt-empty">
    @if($icon)
        {!! $icon !!}
    @else
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
        </svg>
    @endif
    <p class="mt-empty-title">{{ $title }}</p>
    @if($description)
        <p class="mt-empty-sub">{{ $description }}</p>
    @endif
    @if($slot->isNotEmpty())
        <div class="mt-4">{{ $slot }}</div>
    @endif
</div>
