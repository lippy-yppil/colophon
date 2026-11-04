@props(['loan'])

@php
    $overdue = $loan->isOverdue();
    $label = $overdue ? 'TERLAMBAT' : match ($loan->status->value) {
        'requested' => 'DIAJUKAN',
        'approved' => 'DISETUJUI',
        'rejected' => 'DITOLAK',
        'cancelled' => 'DIBATALKAN',
        'borrowed' => 'DIPINJAM',
        'returned' => 'DIKEMBALIKAN',
    };
    $color = $overdue ? 'text-red-700 border-red-700' : match ($loan->status->value) {
        'requested' => 'text-amber-700 border-amber-700',
        'approved', 'borrowed' => 'text-blue-800 border-blue-800',
        'returned' => 'text-green-800 border-green-800',
        default => 'text-gray-600 border-gray-600',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-block border-2 rounded px-2 py-0.5 text-xs font-bold tracking-widest uppercase -rotate-3 $color"]) }}>
    {{ $label }}
</span>