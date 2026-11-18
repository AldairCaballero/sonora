@props(['numero', 'titulo', 'artista', 'duracion', 'actual' => false])
<button type="button" @if ($actual) aria-current="true" @endif
    {{ $attributes->merge(['class' => 'group grid w-full grid-cols-[24px_1fr_auto] items-center gap-3 rounded-md px-2 py-3 text-left transition hover:bg-surface-high aria-[current=true]:bg-surface-high']) }}>
    <span data-numero class="font-mono text-mono-sm text-ink-muted group-aria-[current=true]:text-accent">{{ str_pad($numero, 2, '0', STR_PAD_LEFT) }}</span>
    <span class="min-w-0">
        <span data-titulo class="block truncate text-body-sm font-bold text-ink">{{ $titulo }}</span>
        <span data-artista class="block truncate text-body-sm text-ink-muted">{{ $artista }}</span>
    </span>
    <span data-duracion class="font-mono text-mono-sm text-ink-muted">{{ $duracion }}</span>
</button>