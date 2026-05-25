@props(['status' => '', 'tone' => null])
@php
    // Auto-map common workflow statuses to a tone if none was passed.
    $autoMap = [
        // generic
        'active'    => 'green',
        'inactive'  => 'gray',
        'archived'  => 'gray',
        'draft'     => 'gray',
        // enquiries
        'new'       => 'blue',
        'in_progress' => 'amber',
        'replied'   => 'violet',
        'won'       => 'green',
        'lost'      => 'red',
        // bookings
        'confirmed' => 'green',
        'cancelled' => 'red',
        'pending'   => 'amber',
        'pending_confirmation' => 'amber',
        'pending_payment' => 'amber',
        'paid'      => 'green',
        'partial'   => 'amber',
        'unpaid'    => 'red',
        'completed' => 'green',
        // change requests
        'open'      => 'blue',
        'approved'  => 'green',
        'rejected'  => 'red',
        'closed'    => 'gray',
        // ai extraction
        'queued'    => 'amber',
        'running'   => 'blue',
        'review'    => 'amber',
        'failed'    => 'red',
    ];
    $key = strtolower((string) $status);
    $tone = $tone ?? ($autoMap[$key] ?? 'gray');
    $cls = [
        'green'  => 'mt-pill-green',
        'amber'  => 'mt-pill-amber',
        'red'    => 'mt-pill-red',
        'blue'   => 'mt-pill-blue',
        'gray'   => 'mt-pill-gray',
        'violet' => 'mt-pill-violet',
    ][$tone] ?? 'mt-pill-gray';
    $label = ucwords(str_replace('_', ' ', (string) $status));
@endphp
<span {{ $attributes->merge(['class' => $cls]) }}>
    <span class="inline-block h-1.5 w-1.5 rounded-full bg-current opacity-75"></span>
    {{ $label }}
</span>
