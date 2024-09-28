<?php

namespace App\Models;

use App\Models\GameHero;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{
    use HasFactory;

    /**
     * Get all of the tournaments for the Game
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tournaments(): HasMany
    {
        return $this->hasMany(Tournament::class);
    }

    /**
     * Get all of the heros for the Game
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function heros(): HasMany
    {
        return $this->hasMany(GameHero::class);
    }
}
