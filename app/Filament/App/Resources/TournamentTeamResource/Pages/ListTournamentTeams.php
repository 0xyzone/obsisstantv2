<?php

namespace App\Filament\App\Resources\TournamentTeamResource\Pages;

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
                ->importer(TournamentTeamImporter::class),
            Actions\CreateAction::make()
                ->label('Add Team'),
        ];
    }
}
