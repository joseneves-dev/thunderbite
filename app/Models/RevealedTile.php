<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevealedTile extends Model
{
    use HasFactory;

    protected $fillable = ['game_id', 'prize_id', 'tile_index'];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function prize(): BelongsTo
    {
        return $this->belongsTo(Prize::class);
    }
}
