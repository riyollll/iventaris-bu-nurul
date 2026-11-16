@props(['value'])

@php
    $class = match ($value) {
        'baik', 'selesai' => 'badge-subtle-success',
        'rusak ringan', 'diperbaiki' => 'badge-subtle-warning',
        'rusak berat' => 'badge-subtle-danger',
        default => 'badge-subtle-primary',
    };
@endphp

<span {{ $attributes->class(['badge', $class]) }}>{{ ucfirst($value) }}</span>
