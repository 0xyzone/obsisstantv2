<?php

namespace App\Models;

use App\Models\Tournament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentWebhook extends Model
{
    /**
     * Get the tournament that owns the TournamentWebhook
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }
}
