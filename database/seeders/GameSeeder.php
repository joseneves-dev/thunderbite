<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\RevealedTile;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // First, delete related entries in revealed_tiles
    RevealedTile::query()->delete();
    
    // Then, delete entries in games
    Game::query()->delete();
        Game::factory()->count(10000)->create();
    }
}
