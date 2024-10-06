<?php

namespace App\Filament\App\Resources\TournamentGroupResource\Pages;

use App\Filament\App\Resources\TournamentGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTournamentGroups extends ListRecords
{
    protected static string $resource = TournamentGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
