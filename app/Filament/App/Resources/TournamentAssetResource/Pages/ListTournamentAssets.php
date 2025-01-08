<?php

namespace App\Filament\App\Resources\TournamentAssetResource\Pages;

use App\Filament\App\Resources\TournamentAssetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTournamentAssets extends ListRecords
{
    protected static string $resource = TournamentAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
