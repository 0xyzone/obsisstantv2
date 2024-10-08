<?php

namespace App\Filament\App\Resources\TournamentTeamResource\Pages;

use App\Filament\App\Resources\TournamentTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTournamentTeam extends EditRecord
{
    protected static string $resource = TournamentTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),Actions\Action::make('submit')
            ->label('Save')
            ->action('save')
            ->color('primary')
            ->requiresConfirmation() // Optional: Adds confirmation dialog
            ->button(),
        ];
    }
}
