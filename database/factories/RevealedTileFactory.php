<?php

namespace Database\Factories;

use App\Models\RevealedTile;
use App\Models\Game;
use App\Models\Prize;
use Illuminate\Database\Eloquent\Factories\Factory;

class RevealedTileFactory extends Factory
{
    protected $model = RevealedTile::class;

    public function definition()
    {
        return [
            'game_id' => Game::factory(), // Assuming each RevealedTile is associated with a game
            'prize_id' => Prize::factory(), // Assuming each RevealedTile is associated with a prize
            'tile_index' => $this->faker->numberBetween(0, 24),
            'created_at' => $this->faker->dateTimeThisMonth(),
        ];
    }
}
