# Laravel-PokeApi
proyecto/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── PokemonController.php    # Maneja las peticiones HTTP
│   ├── Models/
│   │   └── Pokemon.php                  # Representa la tabla en BD
│   └── Services/
│       └── PokeApiService.php           # Se conecta a la API externa
├── database/
│   └── migrations/
│       └── xxxx_create_pokemons_table.php  # Crea la tabla en BD
├── routes/
│   └── api.php                          # Define las rutas de la API
├── bootstrap/
│   └── app.php                          # Configuración de rutas
└── .env                                 # Configuración de base de datos
