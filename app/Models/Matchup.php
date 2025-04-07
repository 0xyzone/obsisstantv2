<?php

namespace App\Models;

use App\Models\Tournament;
use App\Models\TournamentTeam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Matchup extends Model
{
    /**
     * Get the tournament that owns the Matchup
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get the teamA that owns the MatchMaking
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teamA(): BelongsTo
    {
        return $this->belongsTo(TournamentTeam::class, 'team_a');
    }

    /**
     * Get the teamB that owns the MatchMaking
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teamB(): BelongsTo
    {
        return $this->belongsTo(TournamentTeam::class, 'team_b');
    }
}
