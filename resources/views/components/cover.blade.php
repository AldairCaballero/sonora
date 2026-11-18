@props(['barras' => [50, 80, 60, 90, 40], 'tamano' => 'md'])
@php
    $tamanos = [
        'sm' => 'size-12 gap-0.5 rounded-sm p-2',
        'md' => 'aspect-square w-full gap-1 rounded-md p-4',
        'lg' => 'size-56 gap-2 rounded-md p-6',
        'xl' => 'size-80 gap-2 rounded-lg p-8 shadow-raise',
    ];
@endphp
<div {{ $attributes->merge(['class' => 'flex items-end bg-surface-high '.$tamanos[$tamano]]) }} aria-hidden="true">
    @foreach ($barras as $altura)
        <i class="flex-1 rounded-pill bg-accent" style="height: {{ $altura }}%"></i>
    @endforeach
</div>