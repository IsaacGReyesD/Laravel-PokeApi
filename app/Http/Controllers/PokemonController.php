<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use App\Services\PokeApiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PokemonController extends Controller
{
    private PokeApiService $pokeApiService;

    public function __construct(PokeApiService $pokeApiService)
    {
        $this->pokeApiService = $pokeApiService;
    }

    public function show(string $name): JsonResponse
    {
        $pokemon = Pokemon::where('name', strtolower($name))->first();

        if ($pokemon) {
            return response()->json([
                'message' => 'Pokemon encontrado en base de datos',
                'source' => 'database',
                'data' => $pokemon
            ]);
        }

        $pokemonData = $this->pokeApiService->getPokemon($name);

        if (!$pokemonData) {
            return response()->json([
                'message' => 'Pokemon no encontrado'
            ], 404);
        }

        $pokemon = Pokemon::create($pokemonData);

        return response()->json([
            'message' => 'Pokemon encontrado y guardado',
            'source' => 'pokeapi',
            'data' => $pokemon
        ], 201);
    }

    public function index(): JsonResponse
    {
        $pokemons = Pokemon::all();

        return response()->json([
            'message' => 'Lista de pokemon',
            'count' => $pokemons->count(),
            'data' => $pokemons
        ]);
    }
}

