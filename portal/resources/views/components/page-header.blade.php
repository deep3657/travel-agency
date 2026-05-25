@props(['title', 'subtitle' => null, 'breadcrumbs' => []])
<div class="mt-page-header mb-6">
    <div>
        @if(!empty($breadcrumbs))
            <nav class="flex items-center gap-1.5 text-xs text-ink-500 mb-1.5" aria-label="Breadcrumb">
                @foreach($breadcrumbs as $i => $crumb)
                    @if(!empty($crumb['href']))
                        <a href="{{ $crumb['href'] }}" class="hover:text-ink-700">{{ $crumb['label'] }}</a>
                    @else
                        <span class="text-ink-700 font-medium">{{ $crumb['label'] }}</span>
                    @endif
                    @if($i < count($breadcrumbs) - 1)
                        <span class="text-ink-300">/</span>
                    @endif
                @endforeach
            </nav>
        @endif
        <h1 class="mt-page-title">{{ $title }}</h1>
        @if($subtitle)
            <p class="mt-page-subtitle">{{ $subtitle }}</p>
        @endif
    </div>
    @if($slot->isNotEmpty())
        <div class="flex items-center gap-2 shrink-0">
            {{ $slot }}
        </div>
    @endif
</div>
