<x-layouts.app titulo="Inicio">
        <div class="mb-6 flex flex-wrap gap-2" role="group" aria-label="Filtrar contenido" data-filtros>
        <x-chip :activo="true" data-filtro="todo">Todo</x-chip>
        <x-chip data-filtro="playlists">Playlists</x-chip>
        <x-chip data-filtro="canciones">Canciones</x-chip>
    </div>

    <h1 class="mb-6 text-title">Tu música de hoy</h1>

    <section data-seccion="playlists" aria-labelledby="titulo-playlists">
        <h2 id="titulo-playlists" class="mb-4 text-heading">Playlists para ti</h2>
                <div class="grid grid-cols-[repeat(auto-fill,minmax(10rem,1fr))] gap-4">
            @foreach ($playlists as $playlist)
                                <x-media-card
                    :titulo="$playlist['nombre']"
                    :artista="$playlist['apoyo']"
                    :duracion="$playlist['duracion']"
                    :barras="$playlist['barras']"
                    :href="route('playlist', $playlist['id'])" />
            @endforeach
        </div>
    </section>

        <section data-seccion="canciones" aria-labelledby="titulo-canciones" class="mt-8">
        <h2 id="titulo-canciones" class="mb-4 text-heading">Para escuchar ahora</h2>
        <ul class="max-w-2xl" data-cola="{{ $paraEscuchar->keys()->implode(',') }}" data-origen="Para escuchar ahora">
            @foreach ($paraEscuchar as $cancion)
                <li>
                    <x-song-row
                        data-cancion="{{ $cancion['id'] }}"
                        :numero="$loop->iteration"
                        :titulo="$cancion['titulo']"
                        :artista="$cancion['artista']"
                        :duracion="$cancion['duracion']" />
                </li>
            @endforeach
        </ul>
    </section>
</x-layouts.app>