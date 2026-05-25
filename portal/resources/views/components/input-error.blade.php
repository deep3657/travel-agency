@props(['messages'])
@if ($messages)
    <ul {{ $attributes->merge(['class' => 'mt-error space-y-0.5 list-none']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
