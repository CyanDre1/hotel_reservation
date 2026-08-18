@props(['status'])

@php
    $labels = [
        'pending' => 'Pending',
        'confirmed' => 'Terkonfirmasi',
        'checked_in' => 'Check-in',
        'checked_out' => 'Check-out',
        'cancelled' => 'Dibatalkan',
    ];

    $classes = [
        'pending' => 'bg-yellow-50 text-warning',
        'confirmed' => 'bg-green-50 text-success',
        'checked_in' => 'bg-blue-50 text-primary',
        'checked_out' => 'bg-blue-50 text-primary',
        'cancelled' => 'bg-red-50 text-danger',
    ];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes[$status] ?? 'bg-surface text-muted' }}">
    {{ $labels[$status] ?? ucfirst($status) }}
</span>
