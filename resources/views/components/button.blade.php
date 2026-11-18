@props(['variant' => 'primary'])
@php
    $variantes = [
        'primary'   => 'border-transparent bg-accent text-on-accent hover:bg-accent-hover',
        'secondary' => 'border-line-strong text-ink hover:bg-surface-high',
        'ghost'     => 'border-transparent text-ink-muted hover:bg-surface-high hover:text-ink',
    ];
@endphp
<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'rounded-pill border-2 px-6 py-3 text-body-sm font-bold transition disabled:pointer-events-none disabled:opacity-50 '.$variantes[$variant],
]) }}>{{ $slot }}</button>