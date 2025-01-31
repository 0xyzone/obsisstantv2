<?php

namespace App\Filament\App\Resources\TournamentCasterResource\Pages;

use App\Filament\App\Resources\TournamentCasterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTournamentCasters extends ListRecords
{
    protected static string $resource = TournamentCasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
