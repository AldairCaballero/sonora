@props(['tamano' => 'md', 'compacto' => false])
@php
    $espacio = [
        'md' => 'gap-6',
        'lg' => 'gap-4 md:gap-8',
    ];

    // En el mini reproductor móvil solo quedan reproducir y siguiente.
    $oculto = $compacto ? 'max-md:hidden' : '';
@endphp
<div {{ $attributes->merge(['class' => 'flex items-center '.$espacio[$tamano]]) }}>
    <x-icon-button etiqueta="Mezclar" icono="shuffle" aria-pressed="false" data-rp="mezclar" class="{{ $oculto }}" />
    <x-icon-button etiqueta="Anterior" icono="skip-prev" data-rp="anterior" class="{{ $oculto }}" />
    <x-icon-button etiqueta="Reproducir" icono="play" variant="primary" :tamano="$tamano" data-rp="reproducir" />
    <x-icon-button etiqueta="Siguiente" icono="skip-next" data-rp="siguiente" />
</div>