<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Carbon\Carbon;

use App\Http\Requests\Api\FlipTileRequest;

use App\Traits\checkGame;

use App\Models\Game;
use App\Models\RevealedTile;

class ApiController extends Controller
{
    use checkGame;

    public function flip(FlipTileRequest $request)
   {
       
        $gameId = $request->input('gameId');
        $tileIndex = $request->input('tileIndex');

        $game = Game::with('campaign')->findOrFail($gameId);
    
        $revealedTilesCount = RevealedTile::where('game_id', $game->id)->count();

        // If no prizes are available, return
        if ($revealedTilesCount->count() >= 10 ) { 
            return response()->json([
                'message' => 'You lost, max moves 10',
            ]);
        }

        // Check if the tile has already been picked
        $tileAlreadyPicked = RevealedTile::where('game_id', $game->id)
            ->where('tile_index', $tileIndex)
            ->first();

        if ($tileAlreadyPicked) {
            return response()->json([
                'tileImage' => asset('assets/'.$tileAlreadyPicked->prize->image),
            ]);
        }

        // Get available prizes 
        $availablePrizes = $this->getAvailablePrizes($game);
        
        // Select a prize based on the number of revealed tiles
        if ($revealedTilesCount < 3) {
            // For the first two tiles, create the illusion of equal chance
            $prize = $availablePrizes->random();
        } else {
            // For the third tile, use weighted selection
            $prize = $this->selectAvailablePrize($availablePrizes);
        }

        RevealedTile::create([
            'game_id' => $game->id,
            'prize_id' => $prize->id,
            'tile_index' => $tileIndex,
        ]);

        // Check for 3 matching tiles
        if ($this->checkWin($game)) {
            $game->update([
                'prize_id' => $prize->id, 
                'revealed_at' => Carbon::now($game->campaign->timezone),
            ]);

            return response()->json([
                'tileImage' => asset('assets/'.$prize->image),
                'message' => 'You won a prize!',
            ]);
        }

        // Return the revealed tile image
        return response()->json([
            'tileImage' => asset('assets/'.$prize->image),
        ]);
   }
}
