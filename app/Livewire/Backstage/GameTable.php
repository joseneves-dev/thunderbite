<?php

namespace App\Livewire\Backstage;

use App\Models\Game;

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
        $this->dispatch('exportData', [
            'account' => $this->account,
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
    
        if (!empty($this->startDate)) {
            $query->whereDate('games.revealed_at', '>=', $this->startDate);
        }
    
        if (!empty($this->endDate)) {
            $query->whereDate('games.revealed_at', '<=', $this->endDate);
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
