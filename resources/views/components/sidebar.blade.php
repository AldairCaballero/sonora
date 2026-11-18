@php
    // Solo enlaces a pantallas que existen. Buscar se suma en la fase 9.
    $enlaces = [
        ['ruta' => 'inicio', 'icono' => 'home', 'etiqueta' => 'Inicio'],
        ['ruta' => 'reproductor', 'icono' => 'play', 'etiqueta' => 'Reproductor'],
    ];
@endphp
<nav aria-label="Principal" class="flex items-center gap-2 border-b border-line bg-surface-raised px-4 py-2 md:flex-col md:items-stretch md:border-b-0 md:py-6">
    <a href="{{ route('inicio') }}" class="flex items-center gap-2 rounded-md md:mb-6">
        <x-logo />
        <span class="text-heading font-extrabold text-ink">Sonora</span>
    </a>

    <ul class="ml-auto flex gap-1 md:ml-0 md:flex-col md:gap-2">
        @foreach ($enlaces as $enlace)
            <li>
                <a href="{{ route($enlace['ruta']) }}"
                   @if (request()->routeIs($enlace['ruta'])) aria-current="page" @endif
                   class="flex items-center gap-2 rounded-md p-2 text-body-sm font-bold text-ink-muted transition hover:bg-surface-high hover:text-ink aria-[current=page]:bg-surface-high aria-[current=page]:text-ink md:gap-3 md:p-3">
                    <x-icon :name="$enlace['icono']" />
                    {{ $enlace['etiqueta'] }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>