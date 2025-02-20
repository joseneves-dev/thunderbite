<?php

use App\Models\Game;
use App\Models\Prize;
use App\Models\Campaign;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;

use App\Traits\checkGame;

class GameWinTraitTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_available_games_returns_true_when_prize_is_available()
    {
        $campaign = Campaign::factory()->create();
        $prize = Prize::factory()->create([
            'campaign_id' => $campaign->id,
            'segment' => 'A',
            'daily_volume' => null, 
        ]);

        $game = Game::factory()->create([
            'campaign_id' => $campaign->id,
            'prize_id' => $prize->id,
            'segment' => 'A',
        ]);

         $trait = new class {
            use CheckGame;
        };

        $this->assertTrue($trait->getAvailableGames($game));

    }

    public function test_get_available_games_returns_false_when_daily_volume_is_exceeded()
    {
        $campaign = Campaign::factory()->create();
        $prize = Prize::factory()->create([
            'campaign_id' => $campaign->id,
            'segment' => 'A',
            'daily_volume' => 2, 
        ]);

        Game::factory()->create([
            'campaign_id' => $campaign->id,
            'prize_id' => $prize->id,
            'segment' => 'A',
            'revealed_at' => Carbon::now(), 
        ]);

        Game::factory()->create([
            'campaign_id' => $campaign->id,
            'prize_id' => $prize->id,
            'segment' => 'A',
            'revealed_at' => Carbon::now(), 
        ]);

        $game = Game::factory()->create([
            'campaign_id' => $campaign->id,
            'prize_id' => $prize->id,
            'segment' => 'A',
        ]);

         $trait = new class {
            use CheckGame;
        };

        $this->assertFalse($trait->getAvailableGames($game));

    }
}
