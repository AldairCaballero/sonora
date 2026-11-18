<x-layouts.app :titulo="$playlist['nombre']">
    <header class="flex flex-wrap items-end gap-6">
        <x-cover tamano="lg" :barras="$playlist['barras']" />
        <div class="min-w-0">
            <p class="text-caption uppercase text-ink-muted">{{ $playlist['apoyo'] }}</p>
            <h1 class="mt-2 text-title">{{ $playlist['nombre'] }}</h1>
            <p class="mt-4 font-mono text-mono text-ink-muted">
                {{ $lista->count() }} {{ $lista->count() === 1 ? 'canción' : 'canciones' }} · {{ $total }}
            </p>
        </div>
    </header>

    <div class="mt-6 flex gap-4">
        <x-button variant="secondary" data-rp="reproducir-lista">Reproducir</x-button>
        <x-button variant="secondary" data-rp="mezclar-lista">Mezclar</x-button>
    </div>

    <ol class="mt-8 max-w-2xl" aria-label="Canciones de la playlist" data-cola="{{ $lista->pluck('id')->implode(',') }}" data-origen="{{ $playlist['nombre'] }}">
        @foreach ($lista as $cancion)
            <li>
                <x-song-row
                    data-cancion="{{ $cancion['id'] }}"
                    :numero="$loop->iteration"
                    :titulo="$cancion['titulo']"
                    :artista="$cancion['artista']"
                    :duracion="$cancion['duracion']" />
            </li>
        @endforeach
    </ol>
</x-layouts.app>