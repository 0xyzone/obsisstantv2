<?php

namespace App\Models;

use App\Models\MatchStat;
use App\Models\Tournament;
use App\Models\TournamentTeam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeamPlayer extends Model
{
    use HasFactory;
    protected $appends = ['photo_url'];

    /**
     * Get the tournament that owns the TeamPlayer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get the team that owns the TeamPlayer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(TournamentTeam::class, "tournament_team_id", "id");
    }

    /**
     * Get all of the stat for the TeamPlayer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stats(): HasMany
    {
        return $this->hasMany(MatchStat::class);
    }

    public function getPhotoUrlAttribute(): string
    {
        if($this->photo) {
            return asset('storage/' . $this->photo); // Assuming $this->image contains the asset path
        }
        return null;
    }
}
