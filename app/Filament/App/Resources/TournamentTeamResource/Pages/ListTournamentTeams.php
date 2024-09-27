<?php

namespace App\Filament\App\Resources\TournamentTeamResource\Pages;

use App\Filament\App\Resources\TournamentTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTournamentTeams extends ListRecords
{
    protected static string $resource = TournamentTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
