<?php

namespace App\Http\Controllers;

use App\Support\Tiempo;

class PlaylistController extends Controller
{
    public function __invoke(string $id)
    {
        $playlist = collect(config('sonora.playlists'))->firstWhere('id', $id);

        // Si el id no existe, Laravel muestra su página 404.
        abort_if($playlist === null, 404);

        $canciones = collect(config('sonora.canciones'))->keyBy('id');

        $lista = collect($playlist['canciones'])->map(function ($idCancion) use ($canciones) {
            $cancion = $canciones[$idCancion];

            return $cancion + ['duracion' => Tiempo::formatear($cancion['segundos'])];
        });

        $total = Tiempo::formatear($lista->sum('segundos'));

        return view('playlist', compact('playlist', 'lista', 'total'));
    }
}