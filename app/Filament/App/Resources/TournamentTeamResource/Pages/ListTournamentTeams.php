<?php

namespace App\Filament\App\Resources\TournamentTeamResource\Pages;

use Filament\Actions;
use App\Models\TournamentTeam;
use Filament\Actions\ImportAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Imports\TeamPlayerImporter;
use App\Filament\Imports\TournamentTeamImporter;
use App\Filament\App\Resources\TournamentTeamResource;

class ListTournamentTeams extends ListRecords
{
    public $hidden = false;
    protected static string $resource = TournamentTeamResource::class;

    protected function getHeaderActions(): array
    {
        $tournament = Filament::getTenant();
        $currentTeamCount = TournamentTeam::where('tournament_id', $tournament->id)->count();
        $maxTeams = $tournament->max_teams;
        if ($currentTeamCount >= $maxTeams) {
            $this->hidden =  true;
        } else {
            $this->hidden = false;
        };
        return [
            // \EightyNine\ExcelImport\ExcelImportAction::make()
            // ->slideOver()
            // ->color("primary"),
            ImportAction::make('teams')
                ->label("Import Teams")
                ->importer(TournamentTeamImporter::class)
                ->hidden(fn (): bool => $this->hidden),
            ImportAction::make('players')
                ->label("Import Players")
                ->importer(TeamPlayerImporter::class),
            Actions\CreateAction::make()
                ->label('Add Team')
                ->hidden(fn (): bool => $this->hidden),
        ];
    }
}
