@props(['titulo', 'artista', 'duracion', 'href' => '#', 'barras' => [50, 80, 60, 90, 40]])
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'group block rounded-md bg-surface-raised p-4 transition hover:bg-surface-high hover:shadow-raise']) }}>
    <div class="mb-4 flex aspect-square items-end gap-1 rounded-md bg-surface-high p-4 transition group-hover:bg-surface-base">
        @foreach ($barras as $altura)
            <i class="flex-1 rounded-pill bg-accent" style="height: {{ $altura }}%"></i>
        @endforeach
    </div>
    <p class="truncate text-heading">{{ $titulo }}</p>
    <p class="mt-1 truncate text-body-sm text-ink-muted">{{ $artista }}</p>
    <p class="mt-2 font-mono text-mono-sm text-ink-muted">{{ $duracion }}</p>
</a>