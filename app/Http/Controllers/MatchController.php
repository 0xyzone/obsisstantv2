<?php

namespace App\Http\Controllers;

use App\Models\MatchMaking;
use App\Models\User;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index(User $user) {
        $activeMatch = MatchMaking::where('is_active', true)->where('user_id', $user->id)->with('teamA', 'teamB')->firstOrFail();
        return $activeMatch;
    }
}
