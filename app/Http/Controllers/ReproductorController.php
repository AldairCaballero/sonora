<?php

namespace App\Http\Controllers;

use App\Support\Tiempo;

class ReproductorController extends Controller
{
    public function __invoke()
    {
        // Por ahora la cola es la primera playlist. En la fase 7 sale de la reproducción real.
        $playlist = config('sonora.playlists')[0];
        $canciones = collect(config('sonora.canciones'))->keyBy('id');

        $cola = collect($playlist['canciones'])->map(function ($id) use ($canciones) {
            $cancion = $canciones[$id];

            return $cancion + ['duracion' => Tiempo::formatear($cancion['segundos'])];
        });

        return view('reproductor', [
            'playlist' => $playlist,
            'cola' => $cola,
            'actual' => $cola->first(),
        ]);
    }
}