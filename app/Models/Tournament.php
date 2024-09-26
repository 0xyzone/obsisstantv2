<?php

namespace App\Models;

use App\Models\Game;
use App\Models\User;
use App\Enums\TournamentType;
use App\Models\TournamentTeam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tournament extends Model
{
    use HasFactory;
    
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tournament_users');
    }

    // public function users(): BelongsTo
    // {
    //     return $this->belongsTo(User::class);
    // }

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

    protected $casts = [
        "type"=> TournamentType::class,
    ];
}
