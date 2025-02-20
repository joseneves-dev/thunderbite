<?php

namespace App\Livewire\Backstage;

use App\Models\Game;

use Carbon\Carbon;

class GameTable extends TableComponent
{
    public $sortField = 'revealed_at';

    public $extraFilters = 'games-filters';

    public $prizeId = null;

    public $account = null;

    public $startDate = null;

    public $endDate = null;

    protected $listeners = ['exportGamesTable' => 'export'];

    public function export()
    {
        $campaignId = session('activeCampaign');

        $this->dispatch('exportData', [
            'account' => $this->account,
            'campaignId' => $campaignId,
            'prizeId' => $this->prizeId,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }

    public function render()
    {
        $columns = [
            [
                'title' => 'account',
                'sort' => true,
            ],

            [
                'title' => 'prize name', 
                'attribute' => 'prize_name',
                'sort' => true,
            ],

            [
                'title' => 'revealed at',
                'attribute' => 'revealed_at',
                'sort' => true,
            ],
        ];

        $this->startDate = Carbon::parse($this->startDate)->format('Y-m-d H:i:s');
        $this->endDate = Carbon::parse($this->endDate)->format('Y-m-d H:i:s');

        $query = Game::query()
        ->join('prizes', 'prizes.id', '=', 'games.prize_id')
        ->where('prizes.campaign_id', session('activeCampaign'))
        ->select(
            'games.*',
            'prizes.name as prize_name'
        );

        if (!empty($this->account)) {
            $query->where('games.account', $this->account);
        }
    
        if (!empty($this->prizeId)) {
            $query->where('games.prize_id', $this->prizeId);
        }
    
        if (!empty($this->startDate) && !empty($this->endDate)) {
            $query->whereBetween('games.revealed_at', [$this->startDate, $this->endDate]);
        } 

        
        $rows = $query->orderBy($this->sortField, $this->sortDesc ? 'DESC' : 'ASC')
                      ->paginate($this->perPage);

        return view('livewire.backstage.table', [
            'columns' => $columns,
            'resource' => 'games',
            'rows' => $rows,
        ]);
    }
}
