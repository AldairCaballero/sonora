@props(['activo' => false])
<button type="button" aria-pressed="{{ $activo ? 'true' : 'false' }}"
    {{ $attributes->merge(['class' => 'rounded-pill border-2 border-transparent bg-surface-high px-4 py-2 text-caption uppercase text-ink transition hover:border-line-strong aria-pressed:bg-accent aria-pressed:text-on-accent']) }}>{{ $slot }}</button>