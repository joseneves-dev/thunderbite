<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

use Carbon\Carbon;

use App\Http\Requests\frontend\LoadCampaignRequest;

use App\Traits\checkGame;

use App\Helpers\JsonHelper;

use App\Models\Campaign;
use App\Models\Game;

class FrontendController extends Controller
{
    use checkGame;

    public function loadCampaign(LoadCampaignRequest $request, Campaign $campaign): View
    {   

        if (Carbon::parse($campaign->starts_at)->isFuture()) {
            return view('frontend.index', ['config' => JsonHelper::createConfig("Campaign didn't start")]);
        }
        
        if (Carbon::parse($campaign->ends_at)->isPast()) {
            return view('frontend.index', ['config' => JsonHelper::createConfig("Campaign has ended")]);
        }

        $account = $request->query('a'); 
        $segment = $request->query('segment'); 

        $game = Game::firstOrCreate([
            'account' => $account,
            'campaign_id' => $campaign->id,
            'segment' => $segment,
            'prize_id' => null,
            'revealed_at' => null,
        ]);

          // Get available prizes using the trait
          $availablePrizes = $this->getAvailableGames($game);

          // If no prizes are available, return a message
          if (!$availablePrizes) {
            return view('frontend.index', ['config' => JsonHelper::createConfig("No more prizes available for today!")]);  
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
