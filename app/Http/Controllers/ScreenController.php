<?php

namespace App\Http\Controllers;

use App\Models\MatchMaking;
use App\Models\MatchStat;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\Request;

class ScreenController extends Controller
{
    public function versus(User $id) {
        $userId = $id->id;
        $tournament = Tournament::query()->whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->where('is_active', true)->firstOrFail();
        $activeMatch = MatchMaking::query()->where('tournament_id', $tournament->id)->where('is_active', true)->with(['teamA', 'teamB'])->firstOrFail();
        return view('screen.assets.versus', compact('tournament', 'activeMatch'));
    }

    public function winner(User $id) {
        $userId = $id->id;
        $tournament = Tournament::query()->whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->where('is_active', true)->firstOrFail();
        $activeMatch = MatchMaking::query()->where('tournament_id', $tournament->id)->where('is_active', true)->with(['teamA', 'teamB'])->firstOrFail();
        return view('screen.assets.winner', compact('tournament', 'activeMatch'));
    }

    public function mvp(User $id) {
        function calculateBrightnessFromRgba($rgbaColor) {
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
        
        $userId = $id->id;
        $tournament = Tournament::query()->whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->where('is_active', true)->firstOrFail();
        $activeMatch = MatchMaking::query()->where('tournament_id', $tournament->id)->where('is_active', true)->with(['teamA', 'teamB'])->firstOrFail();
        $matchWinner = $activeMatch->winner;
        $matchMvp = MatchStat::where('match_making_id', $activeMatch->id)->where('tournament_team_id', $matchWinner->id)->where('is_mvp', true)->with(['team', 'hero'])->firstOrFail();

        $tournamentPrimaryColor = $tournament->primary_color ?: 'rgba(51, 51, 51, 1)';
        $textColor = calculateBrightnessFromRgba($tournamentPrimaryColor) > 125 ? 'text-black' : 'text-white';
        return view('screen.assets.mvp', compact('tournament', 'activeMatch', 'matchMvp', 'tournamentPrimaryColor', 'textColor'));
    }

    public function mvph2h(User $id) {
        $userId = $id->id;
        $tournament = Tournament::query()->whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->where('is_active', true)->firstOrFail();
        $activeMatch = MatchMaking::query()->where('tournament_id', $tournament->id)->where('is_active', true)->with(['teamA', 'teamB'])->firstOrFail();
        return view('screen.assets.mvph2h', compact('tournament', 'activeMatch'));
    }

    public function matchstats(User $id) {
        $userId = $id->id;
        $tournament = Tournament::query()->whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->where('is_active', true)->firstOrFail();
        $activeMatch = MatchMaking::query()->where('tournament_id', $tournament->id)->where('is_active', true)->with(['teamA', 'teamB'])->firstOrFail();
        return view('screen.assets.match-stats', compact('tournament', 'activeMatch'));
    }

    public function teama(User $id) {
        function calculateBrightnessFromRgba($rgbaColor) {
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
        $userId = $id->id;
        $tournament = Tournament::query()->whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->where('is_active', true)->firstOrFail();
        $activeMatch = MatchMaking::query()->where('tournament_id', $tournament->id)->where('is_active', true)->with(['teamA'])->firstOrFail();
        
        $tournamentPrimaryColor = $tournament->primary_color ?: 'rgba(51, 51, 51, 1)';
        $textColor = calculateBrightnessFromRgba($tournamentPrimaryColor) > 125 ? 'text-black' : 'text-white';
        return view('screen.assets.aroster', compact('tournament', 'activeMatch', 'tournamentPrimaryColor', 'textColor'));
    }

    public function teamb(User $id) {
        function calculateBrightnessFromRgba($rgbaColor) {
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
        $userId = $id->id;
        $tournament = Tournament::query()->whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->where('is_active', true)->firstOrFail();
        $activeMatch = MatchMaking::query()->where('tournament_id', $tournament->id)->where('is_active', true)->with(['teamB'])->firstOrFail();
        $tournamentPrimaryColor = $tournament->primary_color ?: 'rgba(51, 51, 51, 1)';
        $textColor = calculateBrightnessFromRgba($tournamentPrimaryColor) > 125 ? 'text-black' : 'text-white';
        return view('screen.assets.broster', compact('tournament', 'activeMatch', 'tournamentPrimaryColor', 'textColor'));
    }
}
