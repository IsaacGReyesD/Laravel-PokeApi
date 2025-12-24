<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PokeApiService
{
    private string $baseUrl = 'https://pokeapi.co/api/v2';

    public function getPokemon(string $nameOrId)
    {
        try {
            $url = "{$this->baseUrl}/pokemon/" . strtolower($nameOrId);
            
            // LOG: Ver la URL que estamos consultando
            Log::info("Consultando PokeAPI: {$url}");
            
            $response = Http::timeout(10)->get($url);

            // LOG: Ver el código de respuesta
            Log::info("Respuesta de PokeAPI - Status: {$response->status()}");

            if ($response->successful()) {
                Log::info("Pokemon encontrado en PokeAPI: {$nameOrId}");
                return $this->formatPokemonData($response->json());
            }

            Log::warning("Pokemon no encontrado en PokeAPI: {$nameOrId}");
            return null;
        } catch (\Exception $e) {
            Log::error('Error fetching pokemon: ' . $e->getMessage());
            return null;
        }
    }

    private function formatPokemonData(array $data): array
    {
        Log::info("Formateando datos del pokemon: " . $data['name']);
        
        return [
            'name' => $data['name'],
            'types' => array_map(function($type) {
                return $type['type']['name'];
            }, $data['types']),
            'abilities' => array_map(function($ability) {
                return $ability['ability']['name'];
            }, $data['abilities']),
            'sprite' => $data['sprites']['front_default'] ?? null,
        ];
    }
}




