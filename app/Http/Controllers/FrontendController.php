<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Traits\checkGame;

use App\Helpers\JsonHelper;

use App\Models\Campaign;
use App\Models\Game;

class FrontendController extends Controller
{
    use checkGame;

    public function loadCampaign(Request $request, Campaign $campaign): View
    {   

        if (!Carbon::parse($campaign->starts_at)->isPast()) {

            $config = JsonHelper::createConfig("Campaign didn't start");

            return view('frontend.index', ['config' => $config]);
        }
    
        if (Carbon::parse($campaign->ends_at)->isPast()) {
            $config = JsonHelper::createConfig("Campaign has ended");

            return view('frontend.index', ['config' => $config]);
        }

        
        $account = $request->query('a'); 
        $segment = $request->query('segment'); 

        $game = Game::firstOrCreate([
            'account' => $account,
            'campaign_id' => $campaign->id,
            'prize_id' => null,
            'revealed_at' => null,
        ],[
            'segment' => $segment,
        ]);

          // Get available prizes using the trait
          $availablePrizes = $this->getAvailableGames($game);

          // If no prizes are available, return a message
          if (!$availablePrizes) {
            $config = JsonHelper::createConfig("No more prizes available for today!");
            return view('frontend.index', ['config' => $config]);  
          }

          
        $revealedTiles = $game->revealedTiles()->with('prize')->get();

        $revealedTilesToArray = $revealedTiles->map(function ($tile) {
            return [
                'index' => $tile->tile_index,
                'image' => asset('assets/'.$tile->prize->image),
            ];
        })->toArray();

        $config = JsonHelper::createConfig(
            null, 
            $game->id, 
            $revealedTilesToArray, 
        );
              
        return view('frontend.index', ['config' => $config]);
    }

    public function placeholder(): View
    {
        return view('frontend.placeholder');
    }
}
