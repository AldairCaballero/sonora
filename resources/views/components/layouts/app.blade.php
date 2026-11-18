@props(['titulo' => null, 'completa' => false])
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $titulo ? $titulo.' · Sonora' : 'Sonora' }}</title>
    <script>try{var t=localStorage.getItem('sonora:tema');if(t==='light'||t==='dark'){document.documentElement.dataset.theme=t}}catch(e){}</script>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="grid h-dvh overflow-hidden {{ $completa ? 'grid-cols-[1fr] grid-rows-[1fr]' : 'grid-cols-1 grid-rows-[auto_1fr_auto] md:grid-cols-[200px_1fr] md:grid-rows-[1fr_80px]' }}">
    <a href="#contenido" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-10 focus:rounded-pill focus:bg-surface-high focus:px-4 focus:py-2 focus:text-body-sm focus:font-bold focus:text-ink">Saltar al contenido</a>

    @unless ($completa)
        <x-sidebar />
    @endunless

    <main id="contenido" tabindex="-1" class="min-h-0 overflow-y-auto outline-none {{ $completa ? '' : 'px-4 py-4 md:px-8 md:py-6' }}">
        {{ $slot }}
    </main>

    @unless ($completa)
        <x-player-bar />
    @endunless

    <script type="application/json" id="catalogo">@json(config('sonora'))</script>

    <template id="iconos-reproduccion">
        <x-icon name="play" data-icono="play" />
        <x-icon name="pause" data-icono="pause" />
    </template>
</body>
</html>