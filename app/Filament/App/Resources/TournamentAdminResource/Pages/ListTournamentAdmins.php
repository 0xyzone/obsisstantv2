<?php

namespace App\Filament\App\Resources\TournamentAdminResource\Pages;

use App\Filament\App\Resources\TournamentAdminResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTournamentAdmins extends ListRecords
{
    protected static string $resource = TournamentAdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
