<?php

namespace App\Models;

use App\Models\MatchStat;
use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\TournamentAdmin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MatchMaking extends Model
{
    use HasFactory;

    /**
     * Get the tournament that owns the MatchMaking
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

    /**
     * Get all of the stats for the MatchMaking
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statsForTeamA(): HasMany
    {
        return $this->hasMany(MatchStat::class, 'tournament_team_id', 'team_a');
    }

    /**
     * Get all of the stats for the MatchMaking
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statsForTeamB(): HasMany
    {
        return $this->hasMany(MatchStat::class, 'tournament_team_id', 'team_b');
    }

    /**
     * Get the winner that owns the MatchMaking
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function winner(): BelongsTo
    {
        return $this->belongsTo(TournamentTeam::class, 'match_winner', 'id');
    }

    /**
     * Get the admin that owns the MatchMaking
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(TournamentAdmin::class, 'tournament_admin_id');
    }
}
