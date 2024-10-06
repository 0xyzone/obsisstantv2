<?php

namespace App\Filament\App\Resources\TournamentGroupResource\Pages;

use App\Filament\App\Resources\TournamentGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTournamentGroup extends CreateRecord
{
    protected static string $resource = TournamentGroupResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
