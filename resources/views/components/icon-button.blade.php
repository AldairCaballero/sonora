@props(['etiqueta', 'icono', 'variant' => 'ghost', 'tamano' => 'md'])
@php
        $variantes = [
        'primary' => 'bg-accent text-on-accent hover:bg-accent-hover',
        'ghost'   => 'text-ink hover:bg-surface-high aria-pressed:bg-surface-high aria-pressed:text-accent',
    ];

    $tamanos = [
        'md' => 'size-10',
        'lg' => 'size-14',
    ];
@endphp
<button {{ $attributes->merge([
    'type' => 'button',
    'aria-label' => $etiqueta,
    'class' => 'inline-flex shrink-0 items-center justify-center rounded-pill transition disabled:pointer-events-none disabled:opacity-50 '.$tamanos[$tamano].' '.$variantes[$variant],
]) }}>
    <x-icon :name="$icono" />
</button>