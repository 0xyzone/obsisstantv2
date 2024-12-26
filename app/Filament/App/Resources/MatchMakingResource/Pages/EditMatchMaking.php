<?php

namespace App\Filament\App\Resources\MatchMakingResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\App\Resources\MatchMakingResource;

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
    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()->extraAttributes(['type' => 'button', 'wire:click' => 'save']);
    }
}
