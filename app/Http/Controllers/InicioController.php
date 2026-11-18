<?php

namespace App\Http\Controllers;

use App\Support\Tiempo;

class InicioController extends Controller
{
    public function __invoke()
    {
        $canciones = collect(config('sonora.canciones'))->keyBy('id');

        // Cada playlist suma la duración de sus canciones.
        $playlists = collect(config('sonora.playlists'))->map(function ($playlist) use ($canciones) {
            $segundos = collect($playlist['canciones'])->sum(fn ($id) => $canciones[$id]['segundos']);

            return $playlist + ['duracion' => Tiempo::formatear($segundos)];
        });

        $paraEscuchar = $canciones->take(5)->map(
            fn ($cancion) => $cancion + ['duracion' => Tiempo::formatear($cancion['segundos'])]
        );

        return view('inicio', compact('playlists', 'paraEscuchar'));
    }
}