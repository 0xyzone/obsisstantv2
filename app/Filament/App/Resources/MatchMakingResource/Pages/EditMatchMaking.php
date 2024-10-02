<?php

namespace App\Filament\App\Resources\MatchMakingResource\Pages;

use App\Filament\App\Resources\MatchMakingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMatchMaking extends EditRecord
{
    protected static string $resource = MatchMakingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('submit')
                ->label('Save')
                ->action('save')
                ->color('primary')
                ->requiresConfirmation() // Optional: Adds confirmation dialog
                ->button(),
        ];
    }
}
