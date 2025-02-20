<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

use Carbon\Carbon;

use App\Models\Game;

class GameController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(): View
    {
        return view('backstage.games.index');
    }

    public function export(Request $request)
    {
        $account = $request->input('account');
        $prizeId = $request->input('prize_id');
        $campaignId = $request->input('campaign_id');
        $fromDate = Carbon::parse($request->input('start_date'))->format('Y-m-d H:i:s');
        $endDate = Carbon::parse($request->input('end_date'))->format('Y-m-d H:i:s');

        $games = Game::filter($account, $campaignId, $prizeId, $fromDate, $endDate)->get();

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=export_games.csv",
        ];

        // Remove games where prize_id is null
        $games = $games->filter(function ($game) {
            return !is_null($game->prize_id); // Only keep games with a prize_id
        });
        
        $callback = function () use ($games) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['ID', 'Account', 'Prize Name', 'Revealed At']);

            foreach ($games as $game) {
                fputcsv($file, [
                    $game->id,
                    $game->account,
                    $game->prize->name,
                    $game->revealed_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
        }
}
