<?php

namespace App\Traits;

use Carbon\Carbon;

use App\Models\Game;
use App\Models\Prize;

trait checkGame
{
    public function checkWin(Game $game): bool
    {
        $revealedTiles = $game->revealedTiles()
        ->groupBy('prize_id')
        ->selectRaw('prize_id, count(*) as count')
        ->get();

        foreach ($revealedTiles as $tile) {
            if ($tile->count >= 3) {
                return true;
            }
        }

        return false;
    }

    public function getAvailableGames(Game $game)
    {
            return Prize::where('segment', $game->segment)
            ->where('campaign_id', $game->campaign->id)
            ->where(function ($query) {
                $query->whereNull('daily_volume') 
                    ->orWhereRaw(
                        '(SELECT COUNT(*) 
                          FROM games AS game 
                          WHERE game.prize_id = prizes.id 
                          AND DATE(game.created_at) = ? 
                          AND game.revealed_at IS NOT NULL) < prizes.daily_volume',
                        [Carbon::now()->toDateString()]
                    );
            })
            ->exists();
    }
  
    public function getAvailablePrizes(Game $game)
    {
        $prizes = Prize::where('segment', $game->segment)
            ->where('campaign_id', $game->campaign->id)
            ->orderByRaw('-LOG(1.0 - RAND()) / weight') 
            ->get();

        // Filter out prizes that have reached their daily volume limit
        $reachLimitPrizes = $prizes->filter(function ($prize) {
            $wonToday = Game::where('prize_id', $prize->id)
                ->whereNotNull('revealed_at')
                ->whereDate('created_at', Carbon::now()->toDateString())
                ->count();
            
            return $wonToday >= $prize->daily_volume; 
        });

        $revealedTiles = $game->revealedTiles()->get(); 
        
        foreach($prizes as $key => $prize){
            if ($reachLimitPrizes->contains('id', $prize->id) && ($revealedTiles->where('prize_id', $prize->id)->count() > 1)) {
                unset($prizes[$key]);  // Removes the item from the collection
             }
        }

        return $prizes;
    }

    public function selectAvailablePrize($prizes)
    {
        $totalWeight = $prizes->sum('weight');
        $random = mt_rand() / mt_getrandmax() * $totalWeight;
    
        foreach ($prizes as $prize) {
            $random -= $prize->weight;
            if ($random <= 0) {
                return $prize;
            }
        }
    
        return $prizes->first(); 
    }
}
