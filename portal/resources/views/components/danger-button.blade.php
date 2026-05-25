<button {{ $attributes->merge(['type' => 'button', 'class' => 'mt-btn-danger']) }}>
    {{ $slot }}
</button>
