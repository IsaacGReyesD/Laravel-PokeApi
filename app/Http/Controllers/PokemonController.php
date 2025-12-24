<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use App\Services\PokeApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    private PokeApiService $pokeApiService;

    public function __construct(PokeApiService $pokeApiService)
    {
        $this->pokeApiService = $pokeApiService;
    }

    // GET /api/pokemon - Listar todos
    public function index(): JsonResponse
    {
        $pokemons = Pokemon::all();

        return response()->json([
            'message' => 'Lista de pokemon',
            'count' => $pokemons->count(),
            'data' => $pokemons
        ]);
    }

    // GET /api/pokemon/{name} - Buscar uno
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

    // GET /api/pokemon-search?name=pika - Buscar por coincidencia parcial
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('name');
        
        if (!$query) {
            return response()->json([
                'message' => 'Debes proporcionar un parámetro de búsqueda'
            ], 400);
        }

        $pokemons = Pokemon::where('name', 'like', '%' . $query . '%')->get();

        return response()->json([
            'message' => 'Resultados de búsqueda',
            'query' => $query,
            'count' => $pokemons->count(),
            'data' => $pokemons
        ]);
    }

    // GET /api/pokemon/type/{type} - Filtrar por tipo
    public function filterByType(string $type): JsonResponse
    {
        $pokemons = Pokemon::whereJsonContains('types', strtolower($type))->get();

        return response()->json([
            'message' => "Pokemon de tipo {$type}",
            'count' => $pokemons->count(),
            'data' => $pokemons
        ]);
    }

    // GET /api/pokemon/ability/{ability} - Filtrar por habilidad
    public function filterByAbility(string $ability): JsonResponse
    {
        $pokemons = Pokemon::whereJsonContains('abilities', strtolower($ability))->get();

        return response()->json([
            'message' => "Pokemon con habilidad {$ability}",
            'count' => $pokemons->count(),
            'data' => $pokemons
        ]);
    }

    // GET /api/pokemon-random - Obtener un pokemon aleatorio
    public function random(): JsonResponse
    {
        $pokemon = Pokemon::inRandomOrder()->first();

        if (!$pokemon) {
            return response()->json([
                'message' => 'No hay pokemon en la base de datos'
            ], 404);
        }

        return response()->json([
            'message' => 'Pokemon aleatorio',
            'data' => $pokemon
        ]);
    }

    // GET /api/pokemon-latest - Últimos pokemon agregados
    public function latest(): JsonResponse
    {
        $pokemons = Pokemon::latest()->take(10)->get();

        return response()->json([
            'message' => 'Últimos pokemon agregados',
            'count' => $pokemons->count(),
            'data' => $pokemons
        ]);
    }

    // GET /api/pokemon-oldest - Primeros pokemon agregados
    public function oldest(): JsonResponse
    {
        $pokemons = Pokemon::oldest()->take(10)->get();

        return response()->json([
            'message' => 'Primeros pokemon agregados',
            'count' => $pokemons->count(),
            'data' => $pokemons
        ]);
    }

    // GET /api/pokemon-stats - Estadísticas generales
    public function stats(): JsonResponse
    {
        $total = Pokemon::count();
        $types = Pokemon::all()->pluck('types')->flatten()->unique()->values();
        $abilities = Pokemon::all()->pluck('abilities')->flatten()->unique()->values();

        return response()->json([
            'message' => 'Estadísticas de la colección',
            'stats' => [
                'total_pokemon' => $total,
                'total_types' => $types->count(),
                'total_abilities' => $abilities->count(),
                'unique_types' => $types,
                'unique_abilities' => $abilities
            ]
        ]);
    }

    // GET /api/pokemon-count - Contar pokemon
    public function count(): JsonResponse
    {
        $count = Pokemon::count();

        return response()->json([
            'message' => 'Total de pokemon en base de datos',
            'count' => $count
        ]);
    }

    // GET /api/pokemon/id/{id} - Buscar por ID específico
    public function findById(int $id): JsonResponse
    {
        $pokemon = Pokemon::find($id);

        if (!$pokemon) {
            return response()->json([
                'message' => 'Pokemon no encontrado'
            ], 404);
        }

        return response()->json([
            'message' => 'Pokemon encontrado',
            'data' => $pokemon
        ]);
    }

    // GET /api/pokemon-paginated - Con paginación
    public function paginated(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $pokemons = Pokemon::paginate($perPage);

        return response()->json([
            'message' => 'Lista paginada de pokemon',
            'data' => $pokemons
        ]);
    }

    // GET /api/pokemon-names - Solo nombres
    public function names(): JsonResponse
    {
        $names = Pokemon::pluck('name');

        return response()->json([
            'message' => 'Lista de nombres',
            'count' => $names->count(),
            'names' => $names
        ]);
    }

    // GET /api/pokemon-multiple-types - Pokemon con múltiples tipos
    public function multipleTypes(): JsonResponse
    {
        $pokemons = Pokemon::whereRaw('JSON_LENGTH(types) > 1')->get();

        return response()->json([
            'message' => 'Pokemon con múltiples tipos',
            'count' => $pokemons->count(),
            'data' => $pokemons
        ]);
    }

    // GET /api/pokemon-single-type - Pokemon con un solo tipo
    public function singleType(): JsonResponse
    {
        $pokemons = Pokemon::whereRaw('JSON_LENGTH(types) = 1')->get();

        return response()->json([
            'message' => 'Pokemon con un solo tipo',
            'count' => $pokemons->count(),
            'data' => $pokemons
        ]);
    }

    // GET /api/pokemon/compare/{name1}/{name2} - Comparar dos pokemon
    public function compare(string $name1, string $name2): JsonResponse
    {
        $pokemon1 = Pokemon::where('name', $name1)->first();
        $pokemon2 = Pokemon::where('name', $name2)->first();

        if (!$pokemon1 || !$pokemon2) {
            return response()->json([
                'message' => 'Uno o ambos pokemon no encontrados'
            ], 404);
        }

        return response()->json([
            'message' => 'Comparación de pokemon',
            'comparison' => [
                'pokemon1' => $pokemon1,
                'pokemon2' => $pokemon2,
                'types_in_common' => array_intersect($pokemon1->types, $pokemon2->types),
                'abilities_in_common' => array_intersect($pokemon1->abilities, $pokemon2->abilities)
            ]
        ]);
    }

} // ⬅️ ESTE ES EL CIERRE DE LA CLASE - No pongas nada después de esto