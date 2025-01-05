<?php

namespace App\Http\Controllers;

use App\Models\MatchMaking;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index(User $user) {
        $activeTournament = Tournament::where('user_id', $user->id)->where('is_active', true)->firstOrFail();
        $activeMatch = MatchMaking::where('is_active', true)->where('user_id', $user->id)->where('tournament_id', $activeTournament->id)->with('teamA', 'teamB')->firstOrFail();
        return $activeMatch;
    }
}
