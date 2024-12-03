<?php

namespace App\Filament\App\Resources\TournamentAdminResource\Pages;

use App\Filament\App\Resources\TournamentAdminResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTournamentAdmin extends EditRecord
{
    protected static string $resource = TournamentAdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
