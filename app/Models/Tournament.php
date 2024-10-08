<?php

namespace App\Models;

use App\Models\Game;
use App\Models\User;
use App\Enums\TournamentType;
use App\Models\TournamentTeam;
use App\Models\TournamentGroup;
use App\Models\TournamentGroupTeam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tournament extends Model implements HasAvatar
{
    use HasFactory;
    
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tournament_users');
    }

    /**
     * Get the game that owns the Tournament
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Get all of the teams for the Tournament
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams(): HasMany
    {
        return $this->hasMany(TournamentTeam::class);
    }

    /**
     * Get all of the groups for the Tournament
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups(): HasMany
    {
        return $this->hasMany(TournamentGroup::class);
    }

    /**
     * Get all of the matches for the Tournament
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matches(): HasMany
    {
        return $this->hasMany(MatchMaking::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(TeamPlayer::class);
    }

    protected $casts = [
        "type"=> TournamentType::class,
    ];

    public function getLogoUrlAttribute(): string
    {
        if($this->logo) {
            return asset('storage/' . $this->logo); // Assuming $this->image contains the asset path
        }
        return null;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->logo ? Storage::url($this->logo) : null ;
    }

    /**
     * Get all of the groupTeams for the Tournament
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groupTeams(): HasMany
    {
        return $this->hasMany(TournamentGroupTeam::class);
    }
}
