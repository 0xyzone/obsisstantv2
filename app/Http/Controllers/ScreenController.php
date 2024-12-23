<?php

namespace App\Http\Controllers;

use App\Models\MatchMaking;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\Request;

class ScreenController extends Controller
{
    public function versus(User $id) {
        $userId = $id->id;
        $tournament = Tournament::query()->whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->where('is_active', true)->first();
        $activeMatch = MatchMaking::query()->where('tournament_id', $tournament->id)->where('is_active', true)->with(['teamA', 'teamB'])->first();
        return view('screen.assets.versus', compact('tournament', 'activeMatch'));
    }
}
