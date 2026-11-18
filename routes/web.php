<?php

use App\Http\Controllers\InicioController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\ReproductorController;
use Illuminate\Support\Facades\Route;

Route::get('/', InicioController::class)->name('inicio');
Route::get('/playlist/{id}', PlaylistController::class)->name('playlist');
Route::get('/reproductor', ReproductorController::class)->name('reproductor');