<?php

namespace App\Models;

use App\Models\TeamPlayer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TournamentTeam extends Model
{
    use HasFactory;

    /**
     * Get the tournament that owns the TournamentTeam
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get all of the players for the TournamentTeam
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function players(): HasMany
    {
        return $this->hasMany(TeamPlayer::class);
    }

    /**
     * Get all of the matches for the Tournament
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matches(): HasMany
    {
        return $this->hasMany(TournamentMatch::class);
    }

    public function getLogoUrlAttribute(): string
    {
        if($this->logo) {
            return asset('storage/' . $this->logo); // Assuming $this->image contains the asset path
        }
        return null; // Assuming $this->image contains the asset path
    }
}
