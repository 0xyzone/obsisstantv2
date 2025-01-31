<?php

namespace App\Models;

use App\Models\Caster;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentCaster extends Model
{
    /**
     * Get the caster that owns the TournamentCaster
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function caster(): BelongsTo
    {
        return $this->belongsTo(Caster::class);
    }

    /**
     * Get the tournament that owns the TournamentCaster
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }
}
