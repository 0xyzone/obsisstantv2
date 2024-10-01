<?php

namespace App\Filament\App\Resources\TournamentTeamResource\Pages;

use App\Filament\Imports\TeamPlayerImporter;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Imports\TournamentTeamImporter;
use App\Filament\App\Resources\TournamentTeamResource;

class ListTournamentTeams extends ListRecords
{
    protected static string $resource = TournamentTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // \EightyNine\ExcelImport\ExcelImportAction::make()
            // ->slideOver()
            // ->color("primary"),
            ImportAction::make()
                ->label("Import Teams")
                ->importer(TournamentTeamImporter::class),
            ImportAction::make()
                ->label("Import Players")
                ->importer(TeamPlayerImporter::class),
            Actions\CreateAction::make()
                ->label('Add Team'),
        ];
    }
}
