<?php

namespace App\Models;

use App\Models\Tournament;
use App\Models\TournamentGroupTeam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TournamentGroup extends Model
{
    use HasFactory;

    /**
     * Get the tournament that owns the TournamentGroup
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get all of the groupTeams for the TournamentGroup
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groupTeams(): HasMany
    {
        return $this->hasMany(TournamentGroupTeam::class);
    }
}
