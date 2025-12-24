<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

Route::get('/', function () {
    return view('welcome');
});

// Rutas API
Route::prefix('api')->group(function () {
    Route::get('/pokemon', [PokemonController::class, 'index']);
    Route::get('/pokemon/{name}', [PokemonController::class, 'show']);
});