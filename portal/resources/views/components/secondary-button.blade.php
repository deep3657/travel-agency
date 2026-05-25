<button {{ $attributes->merge(['type' => 'button', 'class' => 'mt-btn-secondary']) }}>
    {{ $slot }}
</button>
