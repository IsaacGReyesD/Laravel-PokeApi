<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pokemons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->json('types'); // Guardaremos los tipos como JSON
            $table->json('abilities'); // Guardaremos las habilidades como JSON
            $table->string('sprite')->nullable(); // URL de la imagen
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pokemons');
    }
};