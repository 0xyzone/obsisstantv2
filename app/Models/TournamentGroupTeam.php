<?php

namespace App\Models;

use App\Models\Tournament;
use App\Models\TournamentTeam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TournamentGroupTeam extends Model
{
    use HasFactory;

    /**
     * Get the tournament that owns the TournamentGroupTeam
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get the team that owns the TournamentGroupTeam
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(TournamentTeam::class, 'tournament_team_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(TournamentGroup::class,'tournament_group_id');
    }
}
