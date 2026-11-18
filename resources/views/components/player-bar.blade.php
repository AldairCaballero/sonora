@php
    // El HTML inicial muestra la primera canción en pausa. El JavaScript lo pone al día con el estado guardado.
    $cancion = config('sonora.canciones')[0];
    $total = \App\Support\Tiempo::formatear($cancion['segundos']);
@endphp
<section aria-label="Reproductor" data-rp="barra" class="grid grid-cols-[1fr_auto] items-center gap-x-3 gap-y-1 border-t border-line bg-surface-raised px-4 py-2 md:col-span-2 md:grid-cols-[1fr_2fr_1fr] md:gap-0 md:py-0">
    <div class="flex min-w-0 items-center gap-3">
        <a href="{{ route('reproductor') }}" class="flex min-w-0 items-center gap-3 rounded-md">
            <x-cover tamano="sm" data-rp="portada" :barras="array_slice($cancion['barras'], 0, 3)" />
            <span class="min-w-0">
                <span data-rp="titulo" class="block truncate text-body-sm font-bold text-ink">{{ $cancion['titulo'] }}</span>
                <span data-rp="artista" class="block truncate text-body-sm text-ink-muted">{{ $cancion['artista'] }}</span>
            </span>
        </a>
        <x-icon-button etiqueta="Añadir a favoritos" icono="heart" aria-pressed="false" data-rp="favorito" class="max-md:hidden" />
    </div>

    <div class="contents md:flex md:flex-col md:items-center md:gap-2">
        <x-player-controls compacto />
        <x-progress data-rp="progreso" class="max-md:col-span-2" etiqueta="Progreso de la canción" :valor="0" transcurrido="00:00" :total="$total" interactiva />
    </div>

    <div class="hidden items-center justify-end gap-2 text-ink-muted md:flex">
        <x-icon name="volume" />
        <div class="w-24">
            <x-progress data-rp="volumen" etiqueta="Volumen" :valor="70" interactiva />
        </div>
    </div>
</section>