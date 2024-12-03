<?php

namespace App\Filament\App\Resources\TournamentWebhookResource\Pages;

use App\Filament\App\Resources\TournamentWebhookResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTournamentWebhook extends EditRecord
{
    protected static string $resource = TournamentWebhookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
