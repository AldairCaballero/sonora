@props(['etiqueta', 'valor' => 0, 'transcurrido' => null, 'total' => null, 'interactiva' => false])
<div {{ $attributes->merge(['class' => 'flex w-full items-center gap-2 font-mono text-mono-sm text-ink-muted']) }}>
    @if ($transcurrido !== null)
        <span data-actual>{{ $transcurrido }}</span>
    @endif
    <div role="{{ $interactiva ? 'slider' : 'progressbar' }}"
         @if ($interactiva) tabindex="0" @endif
         aria-label="{{ $etiqueta }}"
         aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $valor }}"
         @if ($transcurrido !== null && $total !== null) aria-valuetext="{{ $transcurrido }} de {{ $total }}" @endif
         class="flex-1 rounded-pill py-2 {{ $interactiva ? 'cursor-pointer touch-none' : '' }}">
        <div class="h-1 rounded-pill bg-surface-high">
            <div data-relleno class="h-1 rounded-pill bg-accent" style="width: {{ $valor }}%"></div>
        </div>
    </div>
    @if ($total !== null)
        <span data-total>{{ $total }}</span>
    @endif
</div>