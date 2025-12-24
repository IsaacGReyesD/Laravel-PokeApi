<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    protected $table = 'pokemons'; // Añade esta línea
    
    protected $fillable = [
        'name',
        'types',
        'abilities',
        'sprite'
    ];

    protected $casts = [
        'types' => 'array',
        'abilities' => 'array',
    ];
}