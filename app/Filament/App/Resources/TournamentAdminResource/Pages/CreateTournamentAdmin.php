<?php

namespace App\Filament\App\Resources\TournamentAdminResource\Pages;

use App\Filament\App\Resources\TournamentAdminResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTournamentAdmin extends CreateRecord
{
    protected static string $resource = TournamentAdminResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
