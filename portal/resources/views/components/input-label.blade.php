@props(['value'])
<label {{ $attributes->merge(['class' => 'mt-label']) }}>
    {{ $value ?? $slot }}
</label>
