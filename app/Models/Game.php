<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['campaign_id', 'prize_id', 'account', 'segment','revealed_at', 'is_completed'];

    protected function casts(): array
    {
        return [
            'revealed_at' => 'datetime',
        ];
    }

    public static function filter(?string $account = null, ?int $prizeId = null, ?string $fromDate = null, ?string $tillDate = null)
    {
        $query = self::query();

        if (!empty($account)) {
            $query->where('account', 'LIKE', "%$account%");
        }

        if (!empty($prizeId)) {
            $query->where('prize_id', $prizeId);
        }

        if (!empty($fromDate)) {
            $query->whereDate('revealed_at', '>=', $fromDate);
        }
        if (!empty($tillDate)) {
            $query->whereDate('revealed_at', '<=', $tillDate);
        }

        return $query;
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function prize(): BelongsTo
    {
        return $this->belongsTo(Prize::class);
    }

    public function revealedTiles(): HasMany
    {
        return $this->hasMany(RevealedTile::class);
    }
}
