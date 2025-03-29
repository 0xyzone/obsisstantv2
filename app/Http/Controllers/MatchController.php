<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MatchStat;
use App\Models\Tournament;
use App\Models\MatchMaking;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index(User $user)
    {
        $userId = $user->id;
        $tournament = Tournament::query()->whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->where('is_active', true)->firstOrFail();
        $activeMatch = MatchMaking::where('is_active', true)->where('user_id', $user->id)->where('tournament_id', $tournament->id)->with([
            'teamA',
            'teamB',
            'statsForTeamA' => function ($query) {
                $query->where('tournament_team_id', 'team_a')->with('player'); // Ensure player is loaded
            },
            'statsForTeamB' => function ($query) {
                $query->with('player'); // Ensure player is loaded
            }
        ])->firstOrFail();
        return $activeMatch;
    }

    public function tournament(User $user)
    {
        $userId = $user->id;
        $tournament = Tournament::query()->whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->where('is_active', true)->with([
                    'casters' => function ($query) {
                        $query->orderBy('position', 'asc')->with('caster');
                    }
                ])->firstOrFail();
        return $tournament;
    }
    public function mvp(User $user)
    {
        function calculateBrightnessFromRgba($rgbaColor)
        {
            // Match and extract R, G, B values using a regular expression
            if (preg_match('/rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*[\d.]+)?\)/', $rgbaColor, $matches)) {
                $r = (int) $matches[1];
                $g = (int) $matches[2];
                $b = (int) $matches[3];

                // Calculate brightness using the formula
                return (299 * $r + 587 * $g + 114 * $b) / 1000;
            }

            // Return a default brightness value if the color format is invalid
            return 0;
        }

        $userId = $user->id;
        $tournament = Tournament::query()->whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->where('is_active', true)->firstOrFail();
        $activeMatch = MatchMaking::query()->where('tournament_id', $tournament->id)->where('is_active', true)->with(['teamA', 'teamB'])->firstOrFail();
        $matchWinner = $activeMatch->winner;
        if ($matchWinner) {
            $matchMvp = MatchStat::where('match_making_id', $activeMatch->id)->where('tournament_team_id', $matchWinner->id)->where('is_mvp', true)->with(['team', 'hero'])->firstOrFail();
        } else {
            $matchMvp = null;
        }

        $tournamentPrimaryColor = $tournament->primary_color ?: 'rgba(51, 51, 51, 1)';
        $textColor = calculateBrightnessFromRgba($tournamentPrimaryColor) > 125 ? 'text-black' : 'text-white';
        return view('screen.assets.mvp', compact('tournament', 'activeMatch', 'matchMvp', 'tournamentPrimaryColor', 'textColor'));
    }
}
