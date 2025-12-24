<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

// Rutas básicas
Route::get('/pokemon', [PokemonController::class, 'index']);
Route::get('/pokemon/{name}', [PokemonController::class, 'show']);

// Rutas de búsqueda y filtros
Route::get('/pokemon-search', [PokemonController::class, 'search']);
Route::get('/pokemon/type/{type}', [PokemonController::class, 'filterByType']);
Route::get('/pokemon/ability/{ability}', [PokemonController::class, 'filterByAbility']);

// Rutas de utilidad
Route::get('/pokemon-random', [PokemonController::class, 'random']);
Route::get('/pokemon-latest', [PokemonController::class, 'latest']);
Route::get('/pokemon-oldest', [PokemonController::class, 'oldest']);
Route::get('/pokemon-stats', [PokemonController::class, 'stats']);
Route::get('/pokemon-count', [PokemonController::class, 'count']);
Route::get('/pokemon-names', [PokemonController::class, 'names']);
Route::get('/pokemon-paginated', [PokemonController::class, 'paginated']);
Route::get('/pokemon/id/{id}', [PokemonController::class, 'findById']);