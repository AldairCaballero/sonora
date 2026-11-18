<?php

return [
    'canciones' => [
        ['id' => 1,  'titulo' => 'Escalera al techo',        'artista' => 'Orilla Seca',    'segundos' => 222, 'barras' => [40, 70, 55, 90, 35]],
        ['id' => 2,  'titulo' => 'Papel de estaño',          'artista' => 'Tinta Vegetal',  'segundos' => 245, 'barras' => [80, 45, 65, 30, 75]],
        ['id' => 3,  'titulo' => 'Cuatro minutos de viento', 'artista' => 'Cobre y Humo',   'segundos' => 198, 'barras' => [50, 85, 40, 70, 60]],
        ['id' => 4,  'titulo' => 'Ventana sur',              'artista' => 'Faro Cuarenta',  'segundos' => 301, 'barras' => [30, 60, 90, 50, 80]],
        ['id' => 5,  'titulo' => 'Sal en las manos',         'artista' => 'Orilla Seca',    'segundos' => 176, 'barras' => [65, 35, 80, 55, 45]],
        ['id' => 6,  'titulo' => 'Tren de madrugada',        'artista' => 'Quinta Ventana', 'segundos' => 267, 'barras' => [45, 75, 35, 85, 60]],
        ['id' => 7,  'titulo' => 'Mapa sin norte',           'artista' => 'Tinta Vegetal',  'segundos' => 214, 'barras' => [70, 40, 85, 50, 30]],
        ['id' => 8,  'titulo' => 'Brasas lentas',            'artista' => 'Cobre y Humo',   'segundos' => 252, 'barras' => [55, 90, 45, 65, 75]],
        ['id' => 9,  'titulo' => 'Patio de tierra',          'artista' => 'Faro Cuarenta',  'segundos' => 189, 'barras' => [35, 65, 50, 80, 40]],
        ['id' => 10, 'titulo' => 'Hilo de luz',              'artista' => 'Quinta Ventana', 'segundos' => 231, 'barras' => [85, 50, 70, 35, 60]],
    ],

    'playlists' => [
        ['id' => 'tarde-de-domingo',      'nombre' => 'Tarde de domingo',      'apoyo' => 'Hecha para ti',      'canciones' => [1, 5, 9, 10], 'barras' => [60, 80, 45, 70, 35]],
        ['id' => 'para-cruzar-la-ciudad', 'nombre' => 'Para cruzar la ciudad', 'apoyo' => 'Hecha para ti',      'canciones' => [3, 6, 8, 2],  'barras' => [35, 55, 85, 45, 75]],
        ['id' => 'aguas-quietas',         'nombre' => 'Aguas quietas',         'apoyo' => 'Playlist',           'canciones' => [4, 9, 7, 10], 'barras' => [75, 40, 65, 90, 50]],
        ['id' => 'escritorio-y-cafe',     'nombre' => 'Escritorio y café',     'apoyo' => 'Para concentrarte',  'canciones' => [2, 7, 4, 6],  'barras' => [50, 70, 30, 60, 85]],
        ['id' => 'antes-de-dormir',       'nombre' => 'Antes de dormir',       'apoyo' => 'Playlist',           'canciones' => [4, 9, 10, 5], 'barras' => [40, 85, 55, 35, 70]],
        ['id' => 'salir-temprano',        'nombre' => 'Salir temprano',        'apoyo' => 'Hecha para ti',      'canciones' => [6, 1, 3, 7],  'barras' => [90, 45, 75, 50, 65]],
    ],
];