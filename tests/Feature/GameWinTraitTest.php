<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Game;
use App\Models\Campaign;
use App\Models\Prize;
use App\Models\RevealedTile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Traits\CheckGame;

class GameWinTraitTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_checkWin_returns_true_when_three_or_more_matching_tiles_exist()
    {
        $campaign = Campaign::factory()->create();
        $prize = Prize::factory()->create(['campaign_id' => $campaign->id]);
        $game = Game::factory()->create(['campaign_id' => $campaign->id]);

        RevealedTile::factory()->count(3)->create([
            'game_id' => $game->id,
            'prize_id' => $prize->id,
        ]);

        $trait = new class {
            use CheckGame;
        };

        $this->assertTrue($trait->checkWin($game));
    }

    public function test_checkWin_returns_false_when_less_than_three_matching_tiles_exist()
    {
        $campaign = Campaign::factory()->create();
        $prize = Prize::factory()->create(['campaign_id' => $campaign->id]);
        $game = Game::factory()->create(['campaign_id' => $campaign->id]);

        RevealedTile::factory()->count(2)->create([
            'game_id' => $game->id,
            'prize_id' => $prize->id,
        ]);

        $trait = new class {
            use CheckGame;
        };

        $this->assertFalse($trait->checkWin($game));
    }
}