<?php

namespace App\Models;

use App\Models\Game;
use App\Models\MatchStat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameHero extends Model
{
    use HasFactory;

    /**
     * Get all of the stat for the GameHero
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stats(): HasMany
    {
        return $this->hasMany(MatchStat::class);
    }

    /**
     * Get the game that owns the GameHero
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Get the full URL for the hero image
     *
     * @return string
     */
    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image); // Assuming $this->image contains the asset path
    }
}
