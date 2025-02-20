<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
        $fromDate = $request->input('from_date');
        $endDate = $request->input('end_date');

        $games = Game::filter($account, $prizeId, $fromDate, $endDate)->get();

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=filtered_games.csv",
        ];

        $callback = function () use ($games) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['ID', 'Account', 'Prize ID', 'Revealed At']);

            foreach ($games as $game) {
                fputcsv($file, [
                    $game->id,
                    $game->account,
                    $game->prize_id,
                    $game->revealed_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
        }
}
