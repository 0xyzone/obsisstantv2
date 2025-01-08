<?php

namespace App\Models;

use App\Models\Tournament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentAsset extends Model
{

    /**
     * The attributes that should be appended to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'tournament_overview_url',
        'bracket_url',
        'schedule_url',
    ];
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
     * Accessor for the full URL of the tournament overview image.
     *
     * @return string|null
     */
    public function getTournamentOverviewUrlAttribute(): ?string
    {
        return $this->tournament_overview 
            ? asset('storage/' . $this->tournament_overview) 
            : asset('img/placeholder/1920x1080.png');
    }

    /**
     * Accessor for the full URL of the bracket image.
     *
     * @return string|null
     */
    public function getBracketUrlAttribute(): ?string
    {
        return $this->bracket 
            ? asset('storage/' . $this->bracket) 
            : asset('img/placeholder/1920x1080.png');
    }

    /**
     * Accessor for the full URL of the schedule image.
     *
     * @return string|null
     */
    public function getScheduleUrlAttribute(): ?string
    {
        return $this->schedule 
            ? asset('storage/' . $this->schedule) 
            : asset('img/placeholder/1920x1080.png');
    }
}
