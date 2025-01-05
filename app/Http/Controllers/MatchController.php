<?php

namespace App\Http\Controllers;

use App\Models\MatchMaking;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index(User $user) {
        $userId = $user->id;
        $tournament = Tournament::query()->whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->where('is_active', true)->firstOrFail();
        $activeMatch = MatchMaking::where('is_active', true)->where('user_id', $user->id)->where('tournament_id', $tournament->id)->with('teamA', 'teamB')->firstOrFail();
        return $activeMatch;
    }
}
