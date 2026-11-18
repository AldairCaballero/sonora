<x-layouts.app titulo="Reproductor" :completa="true">
        <div class="grid min-h-full grid-cols-1 md:grid-cols-[1fr_320px]">
                <section aria-label="Reproduciendo" data-rp="escenario" class="relative flex flex-col items-center justify-center gap-4 p-4 md:p-8">
                        <a href="{{ route('inicio') }}" class="self-start rounded-md px-3 py-2 text-body-sm font-bold text-ink-muted transition hover:bg-surface-high hover:text-ink md:absolute md:left-8 md:top-6">Volver al inicio</a>

            <x-cover tamano="xl" data-rp="portada" :barras="$actual['barras']" />

            <div class="text-center">
                <h1 data-rp="titulo" class="text-title">{{ $actual['titulo'] }}</h1>
                <p data-rp="artista" class="mt-1 text-body-sm text-ink-muted">{{ $actual['artista'] }} · {{ $playlist['nombre'] }}</p>
            </div>

            <div class="w-full max-w-105">
                <x-progress data-rp="progreso" etiqueta="Progreso de la canción" :valor="0" transcurrido="00:00" :total="$actual['duracion']" interactiva />
            </div>

            <div class="flex items-center gap-4 md:gap-8">
                <x-player-controls tamano="lg" />
                    <x-icon-button etiqueta="Añadir a favoritos" icono="heart" aria-pressed="false" data-rp="favorito" /> 
            </div>
        </section>

        <aside aria-label="Cola de reproducción" class="overflow-y-auto bg-surface-raised px-4 py-6">
            <h2 class="mb-4 text-heading">A continuación</h2>
            <ol data-rp="cola" data-cola="{{ $cola->pluck('id')->implode(',') }}" data-origen="{{ $playlist['nombre'] }}">
                @foreach ($cola as $cancion)
                    <li>
                        <x-song-row
                            data-cancion="{{ $cancion['id'] }}"
                            :numero="$loop->iteration"
                            :titulo="$cancion['titulo']"
                            :artista="$cancion['artista']"
                            :duracion="$cancion['duracion']"
                            :actual="$loop->first" />
                    </li>
                @endforeach
            </ol>
        </aside>
    </div>

    <template id="plantilla-fila">
        <li><x-song-row data-cancion="0" numero="0" titulo="" artista="" duracion="" /></li>
    </template>
</x-layouts.app>