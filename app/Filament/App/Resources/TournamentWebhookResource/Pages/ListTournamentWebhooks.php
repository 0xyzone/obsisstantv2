<?php

namespace App\Filament\App\Resources\TournamentWebhookResource\Pages;

use App\Filament\App\Resources\TournamentWebhookResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTournamentWebhooks extends ListRecords
{
    protected static string $resource = TournamentWebhookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
