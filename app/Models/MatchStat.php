<?php

namespace App\Models;

use App\Models\GameHero;
use App\Models\TeamPlayer;
use App\Models\MatchMaking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MatchStat extends Model
{
    use HasFactory;

    /**
     * Get the match that owns the MatchStat
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function match(): BelongsTo
    {
        return $this->belongsTo(MatchMaking::class);
    }

    /**
     * Get the player that owns the MatchStat
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(TeamPlayer::class);
    }

    /**
     * Get the hero that owns the MatchStat
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hero(): BelongsTo
    {
        return $this->belongsTo(GameHero::class, 'game_hero_id');
    }
}
