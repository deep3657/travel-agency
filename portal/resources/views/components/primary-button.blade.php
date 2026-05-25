@props(['variant' => 'primary'])
@php
    $cls = match($variant) {
        'accent'    => 'mt-btn-accent',
        'secondary' => 'mt-btn-secondary',
        'danger'    => 'mt-btn-danger',
        default     => 'mt-btn-primary',
    };
@endphp
<button {{ $attributes->merge(['type' => 'submit', 'class' => $cls]) }}>
    {{ $slot }}
</button>
