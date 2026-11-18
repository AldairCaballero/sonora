<?php

namespace App\Support;

class Tiempo
{
    /** Convierte segundos a "mm:ss", o a "h:mm:ss" cuando llega a una hora. */
    public static function formatear(int $segundos): string
    {
        $horas = intdiv($segundos, 3600);
        $minutos = intdiv($segundos % 3600, 60);
        $resto = $segundos % 60;

        return $horas > 0
            ? sprintf('%d:%02d:%02d', $horas, $minutos, $resto)
            : sprintf('%02d:%02d', $minutos, $resto);
    }
}