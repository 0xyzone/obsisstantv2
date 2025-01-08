<?php

namespace App\Http\Controllers;

use App\Models\TournamentAsset;
use App\Models\User;
use App\Models\Tournament;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(User $user)
    {
        $userId = $user->id;
        $tournament = Tournament::query()->whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->where('is_active', true)->firstOrFail();

        $asset = TournamentAsset::where('tournament_id', $tournament->id)->first();

        return $asset ?? [
            "tournament_overview_url" => asset('img/placeholder/1920x1080.png'),
            "bracket_url" => asset('img/placeholder/1920x1080.png'),
            "schedule_url" => asset('img/placeholder/1920x1080.png')
        ];
    }
}
